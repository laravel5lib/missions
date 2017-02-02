<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\v1\Link;
use App\Models\v1\Trip;
use App\Models\v1\Group;
use App\Models\v1\Payment;
use App\Models\v1\Campaign;
use App\Models\v1\Requirement;
use App\Utilities\v1\TeamRole;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TransferData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data from connection.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {   
        // STEP 1
        $progress = $this->output->createProgressBar($campaigns->count());
        $users = $this->users();
        
        $users = $users->map(function($u) use($progress) {
            $progress->advance();
            return $this->get_new_or_existing_user($u);
        });

        $progress->finish();
        $this->line(' Finished transfering users.');

        // STEP 2
        $loading = $this->output->createProgressBar($campaigns->count());
        $groups = $this->groups();
        
        $groups->map(function($g) use($loading) {
            $loading->advance();
            return $this->get_new_or_existing_group($g);
        });

        $loading->finish();
        $this->line(' Finished transfering groups.');

        // STEP 3
        $this->info(
            'Starting transfer of campaigns, trips, and reservations...'
        );
        $campaigns = $this->campaigns();
        $bar = $this->output->createProgressBar($campaigns->count());

        $campaigns->map(function($c) use($bar) {
            $campaign = $this->create_campaign($c);

            // save trips & reservations
            $trips = $this->trips($c->id)->map(function($t) use($campaign) {
                $trip = $this->create_trip($t);

                return $trip;
            })->all();

            $bar->advance();
            return $campaign;
        });

        $bar->finish();
        $this->line(' Transfer finished!');
    }

    /**
     * Create a new campaign
     * 
     * @param  Collection $item
     * @return Collection
     */
    private function create_campaign($item)
    {
        $campaign = new Campaign([
            'name' => $item->name,
            'country_code' => strtolower($item->country_code),
            'short_desc' => null,
            'page_src' => null,
            'avatar_upload_id' => null,
            'banner_upload_id' => null,
            'started_at' => $item->start_date,
            'ended_at' => $item->end_date,
            'published_at' => $item->public ? $item->created_at : null,
            'created_at' => $item->created_at,
            'updated_at' => $item->updated_at
        ]);
        $campaign->save();

        // set slug
        $url = $item->slug ? $item->slug : generate_slug($campaign->name);
        $campaign->slug()->create(['url' => $url]);

        return $campaign;
    }

    /**
     * Create a new trip.
     * 
     * @param  Collection $item
     * @return Object
     */
    private function create_trip($item)
    {
        $trip = new Trip([
            'group_id' => $this->get_new_group_id($item->group_id),
            'spots' => $item->spots,
            'companion_limit' => $item->companion_limit,
            'type' => $this->get_trip_type($item->type),
            'country_code' => $campaign->country_code,
            'difficulty' => 'level_'.$item->difficulty_id,
            'started_at' => $item->start_date,
            'ended_at' => $item->end_date,
            'rep_id' => $this->get_rep_id($item->rep_id),
            'todos' => [
                'Send shirt',
                'Send wrist band',
                'Enter into LGL',
                'Send launch guide',
                'Send luggage tag'
            ],
            'prospects' => $item->prospects,
            'team_roles' => $this->get_team_role_codes($item->team_roles),
            'description' => strip_tags($item->description),
            'public' => $item->public ? true : false,
            'published_at' => Carbon::now(),
            'closed_at' => $item->registration ? 
                            $item->registration : 
                            Carbon::parse($item->start_date)
                                        ->subDays(51)
                                        ->toDateTimeString(),
            'created_at' => $item->created_at,
            'updated_at' => $item->updated_at
        ]);

        $trip->save();
        $this->add_trip_costs($item, $trip);
        $this->add_trip_requirements($item, $trip);
        $this->add_trip_deadlines($item, $trip);

        // $trip->reservations()->saveMany()

        return $trip;
    }

    /**
     * Add trip costs
     * @param  Collection $item
     * @return Collection
     */
    private function add_trip_costs($item, $trip)
    {
        new Cost([
            'name' => 'Deposit',
            'type' => 'static',
            'description' => 'Amount required to secure your spot on the trip.',
            'amount' => $item->cost_deposit,
            'active_at' => Carbon::parse($item->start_date)->subYear()
        ]);

        $super_early = new Cost([
            'name' => 'Super Early Registration',
            'type' => 'incremental',
            'description' => 'Discounted amount for early registration and raising all the funds early.',
            'amount' => $item->cost_super_early,
            'active_at' => Carbon::parse($item->start_date)->subYear()
        ]);

        $early = new Cost([
            'name' => 'Early Registration',
            'type' => 'incremental',
            'description' => 'Discounted amount for early registration and raising all the funds early.',
            'amount' => $item->cost_early,
            'active_at' => $item->super_early_cost_full ?: Carbon::parse($item->start_date)->subDays()
        ]);

        $general = new Cost([
            'name' => 'General Registration',
            'type' => 'incremental',
            'description' => 'Standard registration cost.',
            'amount' => $item->cost_regular,
            'active_at' => $item->early_cost_half ?: Carbon::parse($item->start_date)->subDays(169)
        ]);

        $late = new Cost([
            'name' => 'Late Registration',
            'type' => 'incremental',
            'description' => 'Amount required for registering late.',
            'amount' => $item->cost_regular + $item->cost_late,
            'active_at' => $item->late_fee ?: Carbon::parse($item->start_date)->subDays(48)
        ]);

        $this->trip_addons($item->id)
             ->map(function($addon) use($item) {
                $cost = new Cost([
                    'name' => $addon->name,
                    'type' => 'optional',
                    'description' => $addon->description,
                    'amount' => $addon->cost,
                    'active_at' => Carbon::parse($item->start_date)->subYear()
                ]);
                $cost = $trip->costs()->save($cost);
                $cost->payments()->save(
                    $this->make_payment($addon->cost, 100, null)
                );
                return $cost;
             });

        if ($item->cost_deposit > 0) {
            $cost = $trip->costs()->save($deposit);
            $cost->payments()->save(
                $this->make_payment($cost->amount, 100, null)
            );
        }

        if ($item->cost_super_early > 0) {
            $cost = $trip->costs()->save($super_early);
            $cost->payments()->save(
                $this->make_payment($cost->amount, 100, 
                    $item->super_early_cost_full)
            );
        }

        if ($item->cost_early > 0) {
            $cost = $trips->costs()->save($early);
            $cost->payments()->saveMany([
                $this->make_payment($cost->amount/2, 50, 
                    $item->early_cost_half),
                $this->make_payment($cost->amount/2, 50, 
                    $item->early_cost_full)
            ]);
        }

        if ($item->cost_regular > 0) {
            $cost = $trips->costs()->save($general);
            $cost->payments()->saveMany([
                $this->make_payment($cost->amount/2, 50, 
                    $item->regular_cost_half),
                $this->make_payment($cost->amount/2, 50, 
                    $item->regular_cost_full)
            ]);
        }

        if ($item->cost_late > 0) {
            $cost = $trips->costs()->save($late);
            $cost->payments()->save(
                $this->make_payment($cost->amount, 100, $item->late_fee)
            );
        }
    }

    /**
     * Make a new payment object
     * 
     * @param  Integer  $amount
     * @param  Integer  $percent
     * @param  DateTime  $due
     * @param  integer $grace
     * @return Object
     */
    private function make_payment($amount, $percent, $due, $grace = 3)
    {
        return new Payment([
            'amount_owed' => $amount, 
            'percent_owed' => $percent,
            'due_at' => $due,
            'grace_period' => $grace,
            'upfront' => $due ? false : true
        ]);
    }

    /**
     * Add trip requirements.
     * @param Collection $item
     * @param Collection $trip
     */
    private function add_trip_requirements($item, $trip)
    {
        $requirements = $this->requirements($item->id)
            ->map(function($requirement) use($item) {
                return new Requirement([
                    'name' => $requirement->name,
                    'short_desc' => $requirement->description,
                    'document_type' => $this->get_document_type($requirement),
                    'due_at' => $item->requirements ?: Carbon::parse($item->start_date)->subDays(82),
                    'grace_period' => 3
                ]);
            })->all();

        $trip->requirements()->saveMany($requirements);
    }

    /**
     * Get the document type
     * @param  Collection $requirement
     * @return String
     */
    private function get_document_type($requirement)
    {
        switch ($requirement->formable) {
            case 'MedicalRelease':
                return 'medical_releases';
                break;
            case 'RecommendationRequest':
                return 'referrals';
                break;
            case 'Testimony':
                return 'essays';
                break;
            case 'Passport':
                return 'passports';
                break;
            case 'Visa':
                return 'visas';
                break;
            case 'AirportRequest':
                return 'airport_preferences';
                break;
            default:
                return null;
                break;
        }
    }

    /**
     * Add trip deadlines
     * 
     * @param Collection $item
     * @param Collection $trip
     */
    private function add_trip_deadlines($item, $trip)
    {
        $deadline = new Deadline([
            'name' => 'Last day to add travel companions.',
            'date' => $item->companions ?: Carbon::parse($item->start_date)->subDays(51),
            'grace_period' => 3
        ]);

        $trip->deadlines()->save($deadline);
    }

    /**
     * Get a new group id from the old group id
     * 
     * @param  Integer $old_id
     * @return String 
     */
    private function get_new_group_id($old_id)
    {
        $group = $this->groups($old_id)->map(function($g) {
            return $this->get_new_or_existing_group($g);
        });

        return $group->id;
    }

    /**
     * Create a new group or return existing.
     * 
     * @param  Collection $item
     * @return Object
     */
    private function get_new_or_existing_group($item)
    {
        $group = Group::firstOrNew([
                'name' => $item->name,
                'type' => $item->type,
            ]);

            if (! $group->id) {
               $group->description = $item->description;
               $group->public = $item->public ? true : false;
               $group->timezone = $item->timezone;
               $group->address_one = $item->address_one;
               $group->city = $item->city;
               $group->state = $item->state;
               $group->zip = $item->zip;
               $group->country_code = strtolower($item->country_code);
               $group->phone_one = $item->phone_one;
               $group->email = $item->email;
               $group->status = 'approved';
               $group->created_at = $item->created_at,
               $group->updated_at = $item->updated_at

               $group->save();
               
               // add slug
               $url = $item->slug ? $item->slug : 
                      generate_slug($group->name);
               $group->slug()->create(['url' => $url]);

               // add links
               $links = $this->get_links($item)->map(function($link) {
                    return new Link([
                        'name' => $link->name, 'url' => $link->url
                    ]);
               })->all();
               $group->links()->saveMany($links);

               // add managers
               $managers_ids = $item->manager_ids,
               $group->managers()->sync($this->get_new_user_ids($manager_ids));

            }

            return $group;
    }

    /**
     * Get links
     * 
     * @param  Collection $collection
     * @return Collection
     */
    private function get_links($collection)
    {
        return $collection->filter(function($value, $key) {
            return ends_with($key, '_url') && $value;
        })->map(function($value, $key) {
            return [
                'name' => chop($key, '_url'),
                'url' => remove_http($value)
            ];
        });
    }

    /**
     * Get new user ids from the old ones.
     * 
     * @param  Array $old_ids
     * @return Array
     */
    private function get_new_user_ids($old_ids)
    {
        $ids = is_array($old_ids) ? $old_ids : explode(',', $old_ids);

        $users = $this->users($ids)->map(function($u) {
            return $this->get_new_or_existing_user($u);
        });

        return $users->pluck('id')->toArray();
    }

    /**
     * Get a new user or return existing.
     * 
     * @param  Collection $item
     * @return Object
     */
    private function get_new_or_existing_user($item)
    {
        $user = User::firstOrNew([
            'email' => $item->email
        ]);

        if (! $user->id) {
            $user->name = $item->name;
            $user->password = $item->password;
            $user->gender = strtolower($item->gender);
            $user->status = strtolower($item->status);
            $user->birthday = $item->dob;
            $user->phone_one = $item->cell_phone;
            $user->phone_two = $item->home_phone;
            $user->address = $item->address;
            $user->city = $item->city;
            $user->state = $item->state;
            $user->zip = $item->zip;
            $user->country_code = strtolower($item->country_code);
            $user->timezone = $item->timezone;
            $user->bio = $item->bio;
            $user->public = $item->public ? true : false;
            $user->created_at = $item->created_at;
            $user->updated_at = $item->updated_at;

            $user->save();

            // add slug
            $url = $item->url ? $item->url : 
                   generate_slug($user->name);
            $user->slug()->create(['url' => $url]);
            
            // add links
            $links = $this->get_links($item)->map(function($link) {
                 return new Link([
                     'name' => $link->name, 'url' => $link->url
                 ]);
            })->all();
            $group->links()->saveMany($links);
        }

        return $user;
    }

    /**
     * Match old trip types with new designations
     * 
     * @param  String $type
     * @return String
     */
    private function get_trip_type($type)
    {
        switch ($type) {
            case 'Full Week Medial':
                return 'medical'
                break;
            case 'Full Week Leader':
                return 'leader'
                break;
            case 'Full Week Family Ministry':
                return 'family'
                break;
            default:
                return 'ministry'
                break;
        }
    }

    /**
     * Convert team role names into codes.
     * 
     * @param  String $roles
     * @return Array
     */
    private function get_team_role_codes($roles)
    {
        $roles = collect(explode(',', $roles));
        $roles = $roles->transform(function($role) {
            return TeamRole::get_code($role);
        })->all();

        return $roles;
    }

    /**
     * Get campaigns
     * 
     * @return Collection
     */
    private function campaigns()
    {
        return collect($this->connection()
            ->table('campaigns')
            ->leftJoin('countries', 'countries.id', '=', 'campaigns.country_id')
            ->leftJoin('campaign_pages', 'campaigns.id', '=', 'campaign_pages.campaign_id')
            ->select(
                'campaigns.id', 'campaigns.name', 'abbr as country_code',
                'campaigns.created_at', 'campaigns.updated_at', 'slug',
                'public', 'start_date', 'end_date'
            )
            ->get());
    }

    /**
     * Get trips
     * 
     * @param Integer $campaign_id
     * @return Collection 
     */
    private function trips($campaign_id)
    {
        return collect($this->connection()
            ->table('trips')
            ->leftJoin('trip_types', 'trip_types.id', '=', 'trips.trip_type_id')
            ->leftJoin('trip_deadlines', 'trip_deadlines.trip_id', '=', 'trips.id')
            ->leftJoin('trip_pages', 'trip_pages.trip_id', '=', 'trips.id')
            ->leftJoin('travelerables', function($join) {
                $join->on('travelerables.travelerable_id', '=', 'trips.id')
                     ->where('travelerables.travelerable_type', '=', 'Trip');
            })
            ->leftJoin('travelers', 'travelers.id', '=', 'travelerables.traveler_id')
            ->leftJoin('role_trip', 'role_trip.trip_id', '=', 'trips.id')
            ->leftJoin('roles', 'role_trip.role_id', '=', 'roles.id')
            ->where('campaign_id', $campaign_id)
            ->select(
                'group_id', 'start_date', 'end_date',
                'spots', 'companion_limit', 'difficulty_id',
                'trip_types.type as type', 'trip_deadlines.registration',
                'trips.created_at', 'trips.updated_at', 
                'trips.cost_super_early', 'trips.cost_deposit',
                'trips.cost_early', 'trips.cost_regular', 'trips.cost_late',
                'trip_deadlines.super_early_cost_full',
                'trip_deadlines.early_cost_half',
                'trip_deadlines.early_cost_full',
                'trip_deadlines.regular_cost_half',
                'trip_deadlines.regular_cost_full',
                'trip_deadlines.late_fee',
                'trip_deadlines.requirements'
                'trip_deadlines.companions'
            )->addSelect(
               DB::raw('GROUP_CONCAT(DISTINCT travelers.traveler) AS prospects')
            )->addSelect(
               DB::raw('GROUP_CONCAT(DISTINCT roles.name) AS team_roles')
            )->addSelect(
               DB::raw(CONCAT('### WHAT TO EXPECT', '
                        ', trip_pages.what_to_expect, '
                        ### WHAT\'S INCLUDED IN MY TRIP REGISTRATION?
                        ', trip_pages.included, '
                        ### WHAT\'S NOT INCLUDED IN MY TRIP REGISTRATION?
                        ', trip_pages.not_included, '
                        ### PRE-TRIP TRAINING
                        ', trip_pages.training, '
                        ### HOW YOU WILL GET THERE
                        ', trip_pages.flight_information) AS description)
            )
            ->groupBy('trips.id')
            ->get());
    }

    /**
     * Get Groups
     * 
     * @param  Integer $group_id
     * @return Collection
     */
    private function groups($group_id)
    {
        return collect($this->connection()
            ->table('groups')
            ->leftJoin('countries', 'countries.id', '=', 'groups.country_id')
            ->leftJoin('timezones', 'groups.timezone_id', '=', 'timezones.id')
            ->leftJoin('group_types', 'group_type.id', '=', 'groups.group_type_id')
            ->leftJoin('group_profiles', 'group_profiles.group_id', '=', 'groups.id')
            ->leftJoin('slugs', function($join) {
                $join->on('slugs.slugable_id', '=', 'group_profiles.id')
                     ->where('slugs.slugable_type', '=', 'GroupProfile');
            })
            ->leftJoin('socials', function($join) {
                $join->on('socials.socialable_id', '=', 'groups.id')
                     ->where('socials.socialable_type', '=', 'Group');
            })
            ->leftJoin('group_user', 'group_user.group_id', '=', 'groups.id')
            ->leftJoin('users', 'group_user.user_id', '=', 'users.id')
            ->groupBy('groups.id')
            ->select('groups.name', 'group_types.name AS type', 'slugs.name AS url', 'group_profiles.public', 'group_profiles.short_desc AS description', 'address AS address_one', 'city', 'state', 'zip', 'countries.abbr AS country_code', 'phone AS phone_one', 
                'groups.email', 'timezones.iana AS timezone', 
                'socials.website AS website_url')
            ->addSelect(
                DB::raw('GROUP_CONCAT(DISTINCT users.id) AS manager_ids')
            )
            ->addSelect(DB::raw(" 
                (CASE WHEN socials.facebook IS NOT NULL AND socials.facebook <> '' THEN CONCAT('https://facebook.com/',socials.facebook) ELSE NULL END) AS facebook_url, 
                (CASE WHEN socials.instagram IS NOT NULL AND socials.instagram <> '' THEN CONCAT('https://instagram.com/',socials.instagram) ELSE NULL END) AS instagram_url, 
                (CASE WHEN socials.twitter IS NOT NULL AND socials.twitter <> '' THEN CONCAT('https://twitter.com/',socials.twitter) ELSE NULL END) AS twitter_url, 
                (CASE WHEN socials.linked_in IS NOT NULL AND socials.linked_in <> '' THEN CONCAT('https://linkedin.com/',socials.linked_in) ELSE NULL END) AS linkedin_url, 
                (CASE WHEN socials.google IS NOT NULL AND socials.google <> '' THEN CONCAT('https://plus.google.com/+',socials.google) ELSE NULL END) AS google_url, 
                (CASE WHEN socials.vimeo IS NOT NULL AND socials.vimeo <> '' THEN CONCAT('https://vimeo.com/',socials.vimeo) ELSE NULL END) AS vimeo_url, 
                (CASE WHEN socials.youtube IS NOT NULL AND socials.youtube <> '' THEN CONCAT('https://youtube.com/',socials.youtube) ELSE NULL END) AS youtube_url,
            "))
            ->get());
    }

    /**
     * Get users
     * 
     * @param  Array $ids
     * @return Collection
     */
    private function users($ids)
    {
        return collect($this->connection()
            ->table('users')
            ->leftJoin('timezones', 'users.timezone_id', '=', 'timezones.id')
            ->leftJoin('contacts', 'contacts.user_id', '=', 'users.id')
            ->leftJoin('countries', 'contacts.country_id', '=', 'countries.id')
            ->leftJoin('user_profiles', 'user_profiles.user_id', '=', 'users.id')
            ->leftJoin('slugs', function($join) {
                $join->on('slugs.slugable_id', '=', 'user_profiles.id')
                     ->where('slugs.slugable_type', '=', 'UserProfile');
            })
            ->leftJoin('socials', function($join) {
                $join->on('socials.socialable_id', '=', 'users.id')
                     ->where('socials.socialable_type', '=', 'User');
            })
            ->select(
                'email', 'password', 'address', 'city', 'state', 'zip', 
                'abbr AS country_code', 'home_phone', 'cell_phone', 'gender', 
                'status', 'bio', 'public', 'slugs.name AS url', 
                'iana AS timezone', 'users.created_at', 'users.updated_at',
                'last_login AS login_at', 'socials.website AS website_url'
            )
            ->addSelect(DB::raw('CONCAT(first_name, ' ', last_name) AS name'))
            ->addSelect(DB::raw(" 
                (CASE WHEN socials.facebook IS NOT NULL AND socials.facebook <> '' THEN CONCAT('https://facebook.com/',socials.facebook) ELSE NULL END) AS facebook_url, 
                (CASE WHEN socials.instagram IS NOT NULL AND socials.instagram <> '' THEN CONCAT('https://instagram.com/',socials.instagram) ELSE NULL END) AS instagram_url, 
                (CASE WHEN socials.twitter IS NOT NULL AND socials.twitter <> '' THEN CONCAT('https://twitter.com/',socials.twitter) ELSE NULL END) AS twitter_url, 
                (CASE WHEN socials.linked_in IS NOT NULL AND socials.linked_in <> '' THEN CONCAT('https://linkedin.com/',socials.linked_in) ELSE NULL END) AS linkedin_url, 
                (CASE WHEN socials.google IS NOT NULL AND socials.google <> '' THEN CONCAT('https://plus.google.com/+',socials.google) ELSE NULL END) AS google_url, 
                (CASE WHEN socials.vimeo IS NOT NULL AND socials.vimeo <> '' THEN CONCAT('https://vimeo.com/',socials.vimeo) ELSE NULL END) AS vimeo_url, 
                (CASE WHEN socials.youtube IS NOT NULL AND socials.youtube <> '' THEN CONCAT('https://youtube.com/',socials.youtube) ELSE NULL END) AS youtube_url,
            "))
            ->get());
    }

    /**
     * Get trip addons.
     * 
     * @param  Integer $trip_id
     * @return Collection
     */
    private function trip_addons($trip_id)
    {
        return collect($this->connection()
             ->table('trip_addons')
             ->where('trip_id', $trip_id)
             ->select('name', 'description', 'cost')
             ->get());
    }

    /**
     * Define the database connection
     * 
     * @return Builder
     */
    private function connection()
    {
        return DB::connection('godot');
    }
}
