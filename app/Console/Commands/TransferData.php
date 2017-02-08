<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\v1\Cost;
use App\Models\v1\Link;
use App\Models\v1\Trip;
use App\Models\v1\User;
use App\Models\v1\Donor;
use App\Models\v1\Group;
use App\Models\v1\Upload;
use App\Models\v1\Payment;
use App\Models\v1\Campaign;
use App\Models\v1\Deadline;
use App\Models\v1\Fundraiser;
use App\Models\v1\Requirement;
use App\Models\v1\Reservation;
use App\Models\v1\Transaction;
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
        // $password = $this->secret('What is the password?');

        // if($password <> 'lakn2009t') {
        //     $this->error('Access denied! Invalid password.');
        //     exit;
        // }

        if ($this->confirm('Do you want to reset the database? [y|N]'))
        {
            $this->line('Resetting database...');
            $this->callSilent('migrate:refresh');
            $this->callSilent('db:seed', [
                '--class' => 'BouncerSeeder'
            ]);
            $this->info("\n" . 'Database re-migrated and seeded with defaults.');
        }

        if ($this->confirm('Do you want to import users? [y|N]'))
        {
            // STEP 1
            $this->info('Importing users...');

            $users = $this->users();
            $progress = $this->output->createProgressBar($users->count());
            
            $users = $users->map(function($u) use($progress) {
                $progress->advance();
                return $this->get_new_or_existing_user($u);
            });

            $progress->finish();
            $this->info("\n" . 'Users imported.');
        }

        if ($this->confirm('Do you want to import groups? [y|N]')) {
            // STEP 2
            $this->line("\n" . 'Importing groups...');
            $groups = $this->groups();
            $loading = $this->output->createProgressBar($groups->count());
            
            $groups->map(function($g) use($loading) {
                $loading->advance();
                return $this->get_new_or_existing_group($g);
            });

            $loading->finish();
            $this->info("\n" . 'Groups imported.');
        }

        if ($this->confirm('Do you want to import campaigns, trips, and reservations? [y|N]')) {
            // STEP 3
            $campaigns = $this->campaigns();
            $this->line('Importing Campaigns...' ."\n");

            $campaigns->map(function($c) {
                $campaign = $this->create_campaign($c);

                // save trips & reservations
                $this->line("\n" . 'Importing trips & reservations...');
                $trips = $this->trips($c->id);
                $tripsProgress = $this->output->createProgressBar($trips->count());
                $trips->map(function($t) use($campaign, $tripsProgress) {
                    $trip = $this->create_trip($t, $campaign);
                    $tripsProgress->advance();
                    return $trip;
                })->all();
                $tripsProgress->finish();
                $this->info("\n" . 'Trips and reservations imported.');

                return $campaign;
            });

            $this->info("\n" . 'Campaigns imported.');
        }
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

        // generate a fund for the campaign
        // $fundName = generateFundName($campaign);
        // $fund = $campaign->fund()->create([
        //     'name' => $fundName,
        //     'slug' => generate_fund_slug($fundName),
        //     'balance' => 0,
        //     'class' => generateQbClassName($campaign),
        //     'item' => 'General Donation'
        // ]);
        // Add campaign transactions to the fund
        $transactions = $this->get_transactions($campaign->id, 'Campaign');
        $campaign->fund->transactions()->saveMany($transactions);
        $campaign->fund->reconcile();

        return $campaign;
    }

    /**
     * Create a new trip.
     * 
     * @param  Collection $item
     * @return Object
     */
    private function create_trip($item, $campaign)
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
            'rep_id' => $item->rep_id ? $this->get_new_user_ids([$item->rep_id])[0] : null,
            'todos' => [
                'Send shirt',
                'Send wrist band',
                'Enter into LGL',
                'Send launch guide',
                'Send luggage tag'
            ],
            'prospects' => $item->prospects ? explode(',', $item->prospects) : null,
            'team_roles' => $this->get_team_role_codes($item->team_roles),
            'description' => strip_tags($item->description),
            'public' => true,
            'published_at' => Carbon::now(),
            'closed_at' => $item->registration ? 
                            $item->registration : 
                            Carbon::parse($item->start_date)
                                        ->subDays(51)
                                        ->toDateTimeString(),
            'created_at' => $item->created_at,
            'updated_at' => $item->updated_at
        ]);

        $trip = $campaign->trips()->save($trip);
        $this->add_trip_costs($item, $trip);
        $this->add_trip_requirements($item, $trip);
        $this->add_trip_deadlines($item, $trip);
        $this->add_reservations($item, $trip);

        // $fundName = generateFundName($trip);
        // $trip->fund()->create([
        //     'name' => $fundName,
        //     'slug' => generate_fund_slug($fundName),
        //     'balance' => 0,
        //     'class' => generateQbClassName($trip),
        //     'item' => 'Missionary Donation'
        // ]);

        return $trip;
    }

    /**
     * Add reservations to a trip.
     * 
     * @param Collection $item
     * @param Collection $trip
     */
    private function add_reservations($item, $trip)
    {
        // grab reservations from old sys and map to new format
        $reservations = $this->reservations($item->id)->map(function($r) use($trip) {

            // get a new user id from the old sys user id
            $user_id = $this->get_new_user_ids([$r->user_id])[0];
            $user = User::findOrFail($user_id);

            $reservation = new Reservation([
                'given_names' => $r->first_name.' '.$r->middle_name,
                'surname' => $r->last_name,
                'gender' => $user->gender,
                'status' => $user->status,
                'shirt_size' => $user->shirt_size ?: null,
                'height' => $user->height,
                'weight' => $user->weight,
                'birthday' => $user->birthday,
                'email' => $user->email,
                'phone_one' => $user->phone_one,
                'phone_two' => $user->phone_two,
                'address' => $user->address,
                'city' => $user->city,
                'state' => $user->state,
                'country_code' => $user->country_code,
                // 'desired_role' => $r->role ? $this->get_team_role_codes($r->role)[0] : null,
                'user_id' => $user->id,
                'rep_id' => $r->rep_id ? $this->get_new_user_ids([$r->rep_id])[0] : null,
                'avatar_upload_id' => $user->avatar_upload_id,
                'companion_limit' => $trip->companion_limit,
                'created_at' => $r->created_at,
                'updated_at' => $r->updated_at,
                'deleted_at' => $r->dropped ? Carbon::now() : null
            ]);

            $res = $trip->reservations()->save($reservation);

            // generate a fund for the reservation
            $fundName = generateFundName($res);
            $fund = $res->fund()->create([
                'name' => $fundName,
                'slug' => generate_fund_slug($fundName),
                'balance' => 0,
                'class' => generateQbClassName($res),
                'item' => 'Missionary Donation'
            ]);

            // apply trip costs to the reservation
            $costs = $this->get_reservation_costs($r, $trip);
            $res->costs()->attach($costs);

            // add transactions and update balances
            $transactions = $this->get_transactions($r->id, 'Reservation');
            $fund->transactions()->saveMany($transactions);
            $fund->reconcile();
            $res->payments()->sync();

            // create a fundraiser for the reservation's fund
            $fundraiser = $fund->fundraisers()->save(new Fundraiser([
                'name' => generateFundraiserName($res),
                'url' => $r->slug ?: generate_fundraiser_slug(
                    country($trip->country_code).'-'.$trip->started_at->format('Y')
                ),
                'description' => strip_tags($r->support_description) ?: file_get_contents(resource_path('assets/sample_fundraiser.md')),
                'sponsor_type' => 'users',
                'sponsor_id' => $res->user_id,
                'goal_amount' => $res->getTotalCost(),
                'started_at' => $res->created_at,
                'ended_at' => $trip->started_at,
                'public' => $r->public ? true : false,
            ]));
            // add video to fundraiser
            $fundraiser->uploads()->create([
                'name' => strtolower($r->video_type).'_'.time(),
                'type' => 'video',
                'source' => $r->video_type == 'YouTube' ? '//www.youtube.com/watch?'.$r->video : '//vimeo.com/'.$r->video,
                'meta' => ['format' => strtolower($r->video_type)]
            ]);
            // add photos if they exist
            if($r->photos) {
                $this->add_uploads_to_fundraiser($fundraiser, $r->photos);
            }

            // add requirements, deadlines, and todos
            $res->syncRequirements($trip->requirements);
            $res->syncDeadlines($trip->deadlines);
            $res->addTodos($trip->todos);
        });
    }

    /**
     * Add a list of photos to fundraiser.
     * 
     * @param Collection $fundraiser
     * @param String $photos Comma seperated list
     */
    private function add_uploads_to_fundraiser($fundraiser, $photos)
    {
        $filenames = collect(explode(',', $photos));

        $uploads = $filenames->map(function($file) use($fundraiser) {
            return new Upload([
                'name' => uniqid('fundraiser_'),
                'type' => 'other',
                'source' => 'images/other/'.$file.'.jpg'
            ]);
        })->all();

        $fundraiser->uploads()->saveMany($uploads);
    }

    /**
     * Get all costs applied to the reservation.
     * 
     * @param  Integer $old_res    Old system reservation id
     * @param  Collection $trip    New system trip
     * @return Array               New system cost ids
     */
    private function get_reservation_costs($old_res, $trip)
    {
        // add any static costs
        $static = $trip->costs->where('type', 'static')->pluck('id');

        // determine incremental cost applied
        $incremental = $this->get_applied_incremental_cost($old_res->payment_status, $trip->costs);
        
        // get any optional costs applied
        $optional = $this->get_applied_optional_costs($old_res->id, $trip->costs);

        // push to costs collection and remove any null values
        $costs = $static->push($incremental)->push($optional)->reject(function($cost) { 
            return $cost == null;
        })->all();

        return $costs;
    }

    /**
     * Get any reservation addons as applied optional costs.
     * 
     * @param  integer $id       Old system reservation id
     * @param  Collection $costs New system Trip costs
     * @return Array             New system cost ids
     */
    private function get_applied_optional_costs($id, $costs)
    {
        // grab all the reservation addon names from old system
        $addons = $this->reservation_addons($id)->transform(function($addon) {
            return ucwords(strtolower($addon->name));
        })->pluck('name')->all();

        // find matching cost names with addon names
        // and return the ids
        return $costs->filter(function($cost) use($addons) {
            return in_array($cost->name, $addons);
        })->pluck('id')->all();
    }

    /**
     * Get the current incremenal cost applied by looking
     * at the reservations payment status in the old sys.
     * 
     * @param  Integer $payment_status
     * @param  Collection $costs New sys trip costs
     * @return String New sys cost id.
     */
    private function get_applied_incremental_cost($payment_status, $costs)
    {   
        switch ($payment_status) {
            case 1:
                return $costs->where('name', 'Early Registration')->pluck('id')->first();
                break;
            case 2:
                return $costs->where('name', 'General Registration')->pluck('id')->first();
                break;
            case 3:
                return $costs->where('name', 'Late Registration')->pluck('id')->first();
                break;
            case 4:
                return $costs->where('name', 'Super Early Registration')->pluck('id')->first();
                break;
            default:
                return $costs->where('name', 'General Registration')->pluck('id')->first();
                break;
        }
    }

    /**
     * Get transactions from old sys, format and save to new.
     * 
     * @param  Integer $reservation_id Old sys reservation Id
     * @return Collection New sys transactions
     */
    private function get_transactions($id, $type)
    {
        // get old sys transactions, map to new format and save
        return $this->transactions($id, $type)->map(function($trans) {
            $transaction = new Transaction([
                'amount' => $trans->amount,
                'type' => $trans->payment_method_type == 'Credit' ? 'credit' : 'donation',
                'details' => $this->get_transaction_details($trans),
                'donor_id' => $trans->donor ? $this->get_donor_id($trans) : null,
                'anonymous' => $trans->anonymous,
                'created_at' => $trans->created_at,
                'updated_at' => $trans->updated_at,
                'deleted_at' => $trans->deleted_at
            ]);

            return $transaction;
        })->all();
    }

    private function get_transaction_details($trans)
    {
        switch ($trans->payment_method_type) {
            case 'CreditCard':
                return [
                    'type' => 'card',
                    'comment' => trim(strip_tags($trans->comment)),
                    'auth_id' => $trans->auth,
                    'trans_id' => $trans->trans_id,
                    'brand' => $trans->card_type,
                    'last_four' => substr(trim($trans->cc_number), -4),
                    'cardholder' => $trans->donor,
                ];
                break;
            case 'Check':
                return [
                    'type' => 'check',
                    'comment' => trim(strip_tags($trans->comment)),
                    'number' => $trans->check_number
                ];
                break;
            case 'Cash':
                return [
                    'type' => 'cash',
                    'comment' => trim(strip_tags($trans->comment))
                ];
                break;
            case 'Credit':
                return [
                    'reason' => trim($trans->reason)
                ];
                break;
            default:
                return null;
                break;
        }
    }

    private function get_donor_id($trans) {
        if ($trans->user_id) {
            $user_id = $this->get_new_user_ids([$trans->user_id])[0];
            $user = User::findOrFail($user_id);

            $donor = Donor::firstOrNew([
                'account_id' => $user->id,
                'account_type' => 'users'
            ]);

            if (! $donor->id) {
                $donor->fill([
                    'name' => $user->name,
                    'email' => $user->email,
                    'address' => $user->address ?: $trans->address,
                    'city' => $user->city ?: $trans->city,
                    'state' => $user->state ?: $trans->state,
                    'zip' => $user->zip ?: $trans->zip,
                    'country_code' => $user->country_code ?: strtolower($trans->country_code),
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at
                ]);
                $donor->save();
            }

            return $donor->id;
        }

        $donor = Donor::firstOrNew([
            'name' => $trans->donor,
            'email' => $trans->email
        ]);

        if (! $donor->id) {
            $donor->fill([
                'address' => $trans->address,
                'city' => $trans->city,
                'state' => $trans->state,
                'zip' => $trans->zip,
                'country_code' => strtolower($trans->country_code),
                'created_at' => $trans->created_at,
                'updated_at' => $trans->updated_at
            ]);
            $donor->save();
        }

        return $donor->id;
    }

    /**
     * Add trip costs
     * 
     * @param  Collection $item
     * @return Collection
     */
    private function add_trip_costs($item, $trip)
    {
        // Determine cost deadlines by using the trip_deadline relationship.
        // If none exist, then use default deadline calculated by trip
        // start_date minus a predetermined number of days.
        $active_at = Carbon::parse($item->start_date)->subYear();
        $super_early_deadline = $item->super_early_cost_full ?: Carbon::parse($item->start_date)->endOfYear();
        $early_half_deadline = $item->early_cost_half ?: Carbon::parse($item->start_date)->subDays(169);
        $early_full_deadline = $item->early_cost_full ?: Carbon::parse($item->start_date)->subDays(112);
        $general_half_deadline = $item->regular_cost_half ?: Carbon::parse($item->start_date)->subDays(129);
        $general_full_deadline = $item->regular_cost_full ?: Carbon::parse($item->start_date)->subDays(64);
        $late_deadline = $item->late_fee ?: Carbon::parse($item->start_date)->subDays(48);
        $addon_deadline = $item->regular_cost_full ? Carbon::parse($item->regular_cost_full)->subDays(30) : Carbon::parse($item->start_date)->subDays(94);

        // Set the deposit cost
        // if there is a deposit, we save the cost to the trip
        // and add a single upfront payment for the full amount
        if ($item->cost_deposit > 0) {
            $deposit = new Cost([
                'name' => 'Deposit',
                'type' => 'static',
                'description' => 'Amount required to secure your spot on the trip.',
                'amount' => $item->cost_deposit,
                'active_at' => $active_at
            ]);
            $cost = $trip->costs()->save($deposit);
            $cost->payments()->save(
                $this->make_payment($cost->amount, 100, null)
            );
        }

        // Set the super early registration cost
        // if there is a super early registration cost, we save the cost 
        // to the trip and add a single payment for the full amount
        if ($item->cost_super_early > 0) {
            $super_early = new Cost([
                'name' => 'Super Early Registration',
                'type' => 'incremental',
                'description' => 'Discounted amount for early registration and raising all the funds early.',
                'amount' => $item->cost_super_early - $item->cost_deposit,
                'active_at' => $active_at
            ]);
            $cost = $trip->costs()->save($super_early);
            $cost->payments()->save(
                $this->make_payment($cost->amount, 100, $super_early_deadline)
            );
        }

        // Set the early registration cost
        // if there is an early registration cost, we save the cost
        // to the trip and add two payments each at 50%
        if ($item->cost_early > 0) {
            $early = new Cost([
                'name' => 'Early Registration',
                'type' => 'incremental',
                'description' => 'Discounted amount for early registration and raising all the funds early.',
                'amount' => $item->cost_early - $item->cost_deposit,
                'active_at' => $super_early_deadline
            ]);
            $cost = $trip->costs()->save($early);
            $cost->payments()->saveMany([
                $this->make_payment($cost->amount/2, 50, $early_half_deadline),
                $this->make_payment($cost->amount/2, 50, $early_full_deadline)
            ]);
        }

        // Set the general registration cost
        // if there is a general registration cost, we save the cost
        // to the trip and add two payments each at 50%
        if ($item->cost_regular > 0) {
            $general = new Cost([
                'name' => 'General Registration',
                'type' => 'incremental',
                'description' => 'Standard registration cost.',
                'amount' => $item->cost_regular - $item->cost_deposit,
                'active_at' => $early_half_deadline
            ]);
            $cost = $trip->costs()->save($general);
            $cost->payments()->saveMany([
                $this->make_payment($cost->amount/2, 50, $general_half_deadline),
                $this->make_payment($cost->amount/2, 50, $general_full_deadline)
            ]);
        }

        // Set the late registration cost
        // if there is a late registration cost, we save the cost
        // to the trip and add a single payment for the full amount.
        if ($item->cost_late > 0) {
            $late = new Cost([
                'name' => 'Late Registration',
                'type' => 'incremental',
                'description' => 'Amount required for registering late.',
                'amount' => ($item->cost_regular - $item->cost_deposit) + $item->cost_late,
                'active_at' => $late_deadline
            ]);
            $cost = $trip->costs()->save($late);
            $cost->payments()->save(
                $this->make_payment($cost->amount, 100, $late_deadline)
            );
        }

        // Save any trip addons as optional costs
        // and add a single payment to each
        $this->trip_addons($item->id)
             ->map(function($addon) use($item, $trip, $active_at, $addon_deadline) {
                $cost = new Cost([
                    'name' => ucwords(strtolower($addon->name)),
                    'type' => 'optional',
                    'description' => $addon->description,
                    'amount' => $addon->cost,
                    'active_at' => $active_at
                ]);
                $cost = $trip->costs()->save($cost);
                $cost->payments()->save(
                    $this->make_payment($addon->cost, 100, $addon_deadline)
                );
                return $cost;
             });
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
        $requirements = $this->trip_requirements($item->id)
            ->reject(function($requirement) {
                return is_null($this->get_document_type($requirement));
            })
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
        })->first();

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
                'name' => ucwords($item->name),
                'type' => str_replace('-', '', strtolower($item->type))
            ]);

            if (! $group->id) {
               $group->description = trim($item->description);
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
               $group->avatar_upload_id = trim($item->logo_src) ? $this->get_avatar_id($item->logo_src, $item->name) : null;
               $group->created_at = $item->created_at;
               $group->updated_at = $item->updated_at;

               $group->save();
               
               // add slug
               $url = $item->slug ? $item->slug : generate_slug($group->name);
               $group->slug()->create(['url' => $url]);

               // add links
               $links = $this->get_links($item)->map(function($link) {
                    return new Link([
                        'name' => $link['name'], 'url' => $link['url']
                    ]);
               })->all();
               $group->links()->saveMany($links);

               // add managers
               if ($item->manager_ids)
                    $group->managers()->sync($this->get_new_user_ids($item->manager_ids));

            }

            return $group;
    }

    /**
     * Get avatar id
     * 
     * @param  String $source 
     * @param  String $name   
     * @return String         
     */
    private function get_avatar_id($source, $name)
    {
        $upload = Upload::firstOrNew([
            'source' => 'images/avatars/'.$source
        ]);

        if (! $upload->id) {
            $upload->name = str_slug($name);
            $upload->type = 'avatar';
            $upload->save();
        }

        return $upload->id;
    }

    /**
     * Get links
     * 
     * @param  Collection $collection
     * @return Collection
     */
    private function get_links($collection)
    {
        return collect($collection)->filter(function($value, $key) {
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
            $user->status = $item->status <> '' ? strtolower($item->status) : 'single';
            $user->shirt_size = strtolower($item->shirt_size);
            $user->height = convert_to_cm($item->height_ft, $item->height_in);
            $user->weight = convert_to_kg($item->weight);
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
            $user->avatar_upload_id = trim($item->profile_pic_src) ? $this->get_avatar_id($item->profile_pic_src, $item->name) : null;
            $user->created_at = $item->created_at;
            $user->updated_at = $item->updated_at;

            $user->save();

            if($item->admin > 0) {
                $user->assign('admin');
            } else {
                $user->assign('member');
            }

            // add slug
            $url = $item->url ? $item->url : generate_slug($user->name);
            $user->slug()->create(['url' => $url]);
            
            // add links
            $links = $this->get_links($item)->map(function($link) {
                 return new Link([
                     'name' => $link['name'], 'url' => $link['url']
                 ]);
            })->all();
            $user->links()->saveMany($links);
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
            case 'Full Week Medical':
                return 'medical';
                break;
            case 'Half Week Leader':
                return 'leader';
                break;
            case 'Full Week Family Ministry':
                return 'family';
                break;
            case 'Full Week Ministry (Miami Not Included)':
                return 'ministry (miami not included)';
                break;
            default:
                return 'ministry';
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
                'campaigns.id', 'campaigns.name', 'abbr AS country_code',
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
            ->groupBy('trips.id')
            ->select(
                'trips.id', 'group_id', 'start_date', 'end_date',
                'spots', 'companion_limit', 'difficulty_id', 'rep_id',
                'trip_types.type AS type', 'trip_deadlines.registration',
                'trips.created_at', 'trips.updated_at', 
                'trips.cost_super_early', 'trips.cost_deposit',
                'trips.cost_early', 'trips.cost_regular', 'trips.cost_late',
                'trip_deadlines.super_early_cost_full',
                'trip_deadlines.early_cost_half',
                'trip_deadlines.early_cost_full',
                'trip_deadlines.regular_cost_half',
                'trip_deadlines.regular_cost_full',
                'trip_deadlines.late_fee',
                'trip_deadlines.requirements',
                'trip_deadlines.companions'
            )->addSelect(
               DB::raw('GROUP_CONCAT(DISTINCT travelers.traveler) AS prospects')
            )->addSelect(
               DB::raw('GROUP_CONCAT(DISTINCT roles.name) AS team_roles')
            )->addSelect(
               DB::raw("CONCAT('###WHAT TO EXPECT', '
                        ', trip_pages.what_to_expect, '
                        ###WHAT\'S INCLUDED IN MY TRIP REGISTRATION?
                        ', trip_pages.included, '
                        ###WHAT\'S NOT INCLUDED IN MY TRIP REGISTRATION?
                        ', trip_pages.not_included, '
                        ###PRE-TRIP TRAINING
                        ', trip_pages.training, '
                        ###HOW YOU WILL GET THERE
                        ', trip_pages.flight_information) AS description")
            )
            ->get());
    }

    /**
     * Get Groups
     * 
     * @param  Integer $group_id
     * @return Collection
     */
    private function groups($group_id = null)
    {
        $groups = $this->connection()->table('groups');

        if ($group_id) {
            $groups = $groups->where('groups.id', $group_id);
        }

        $groups = $groups->leftJoin('countries', 'countries.id', '=', 'groups.country_id')
            ->leftJoin('timezones', 'groups.timezone_id', '=', 'timezones.id')
            ->leftJoin('group_types', 'group_types.id', '=', 'groups.group_type_id')
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
            ->select('groups.id', 'groups.name', 'group_types.name AS type', 'slugs.name AS slug', 'group_profiles.public', 'group_profiles.short_desc AS description', 'address AS address_one', 'city', 'state', 'zip', 'countries.abbr AS country_code', 'phone AS phone_one', 'logo_src', 
                'groups.email', 'timezones.iana AS timezone', 
                'socials.website AS website_url', 'groups.created_at', 'groups.updated_at')
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
                (CASE WHEN socials.youtube IS NOT NULL AND socials.youtube <> '' THEN CONCAT('https://youtube.com/',socials.youtube) ELSE NULL END) AS youtube_url
            "));
            
            $groups = collect($groups->get());

            return $groups;
    }

    /**
     * Get users
     * 
     * @param  Array $ids
     * @return Collection
     */
    private function users($ids = [])
    {
        $users = $this->connection()->table('users');

        if ($ids <> []) {
            $users = $users->whereIn('users.id', $ids);
        }
        
        $users = $users->leftJoin('timezones', 'users.timezone_id', '=', 'timezones.id')
            ->leftJoin('shirt_sizes', 'shirt_sizes.id', '=', 'users.shirt_size_id')
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
            ->leftJoin('permissions', 'permissions.user_id', '=', 'users.id')
            ->select(
                'users.id', 'email', 'password', 'address', 'city', 'state', 'zip', 
                'abbr AS country_code', 'home_phone', 'cell_phone', 'gender', 
                'status', 'bio', 'public', 'slugs.name AS url', 
                'iana AS timezone', 'users.created_at', 'users.updated_at',
                'last_login AS login_at', 'socials.website AS website_url', 'dob',
                'profile_pic_src', 'shirt_sizes.name AS shirt_size', 'height_ft',
                'height_in', 'weight'
            )
            ->addSelect(DB::raw("CASE WHEN permissions.user_id IS NOT NULL THEN 1 ELSE 0 END AS admin"))
            ->addSelect(DB::raw("CONCAT(first_name, ' ', last_name) AS name"))
            ->addSelect(DB::raw(" 
                (CASE WHEN socials.facebook IS NOT NULL AND socials.facebook <> '' THEN CONCAT('https://facebook.com/',socials.facebook) ELSE NULL END) AS facebook_url, 
                (CASE WHEN socials.instagram IS NOT NULL AND socials.instagram <> '' THEN CONCAT('https://instagram.com/',socials.instagram) ELSE NULL END) AS instagram_url, 
                (CASE WHEN socials.twitter IS NOT NULL AND socials.twitter <> '' THEN CONCAT('https://twitter.com/',socials.twitter) ELSE NULL END) AS twitter_url, 
                (CASE WHEN socials.linked_in IS NOT NULL AND socials.linked_in <> '' THEN CONCAT('https://linkedin.com/',socials.linked_in) ELSE NULL END) AS linkedin_url, 
                (CASE WHEN socials.google IS NOT NULL AND socials.google <> '' THEN CONCAT('https://plus.google.com/+',socials.google) ELSE NULL END) AS google_url, 
                (CASE WHEN socials.vimeo IS NOT NULL AND socials.vimeo <> '' THEN CONCAT('https://vimeo.com/',socials.vimeo) ELSE NULL END) AS vimeo_url, 
                (CASE WHEN socials.youtube IS NOT NULL AND socials.youtube <> '' THEN CONCAT('https://youtube.com/',socials.youtube) ELSE NULL END) AS youtube_url
            "));

            $users = $users->get();

            return collect($users);
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
     * Get Trip Requirements
     * 
     * @param  integer $trip_id 
     * @return Collection          
     */
    private function trip_requirements($trip_id)
    {
        return collect($this->connection()
            ->table('requireables')
            ->where('requireable_id', $trip_id)
            ->where('requireable_type', 'Trip')
            ->join('requirements', 'requireables.requirement_id', '=', 'requirements.id')
            ->select('name', 'description', 'formable')
            ->get());
    }

    /**
     * Get reservations
     * 
     * @param  Integer $trip_id 
     * @return Collection          
     */
    private function reservations($trip_id)
    {
        return collect($this->connection()
            ->table('reservations')
            ->where('reservations.trip_id', $trip_id)
            ->leftJoin('reservation_pages', 'reservation_pages.reservation_id', '=', 'reservations.id')
            ->join('users', 'users.id', '=', 'reservations.user_id')
            ->join('roles', 'roles.id', '=', 'reservations.role_id')
            ->leftJoin('contacts', 'contacts.user_id', '=', 'users.id')
            ->leftJoin('uploads', function($join) {
                $join->on('reservation_pages.id', '=', 'uploads.uploadable_id')
                     ->where('uploads.uploadable_type', '=', 'ReservationPage');
            })
            ->select(
                'first_name', 'middle_name', 'last_name', 'reservations.user_id', 'roles.name AS role',
                'dropped', 'shirt_sent', 'launch_guide_sent', 'luggage_tag_sent',
                'sent_to_lgl', 'rep_id', 'reservations.created_at', 'reservations.updated_at',
                'support_description', 'reservation_pages.slug', 'reservation_pages.public',
                'reservations.status_id AS payment_status', 'reservations.id',
                'video', 'video_type'
            )
            ->addSelect(
                DB::raw('GROUP_CONCAT(DISTINCT uploads.uuid) AS photos')
            )
            ->groupBy('reservations.id')
            ->distinct()
            ->get());
    }

    private function reservation_addons($reservation_id)
    {
        return collect($this->connection()
            ->table('reservation_addons')
            ->where('reservation_id', $reservation_id)
            ->join('trip_addons', 'trip_addons.id', '=', 'reservation_addons.trip_addon_id')
            ->select('name', 'cost')
            ->get());
    }

    private function transactions($transactionable_id, $transactionable_type)
    {
        return collect($this->connection()
            ->table('transactions')
            ->where('transactionable_id', $transactionable_id)
            ->where('transactionable_type', $transactionable_type)
            ->leftJoin('payment_creditcards AS cards', function($join) {
                $join->on('cards.id', '=', 'transactions.payment_method_id')
                     ->where('transactions.payment_method_type', '=', 'CreditCard');
            })
            ->leftJoin('payment_checks AS checks', function($join) {
                $join->on('checks.id', '=', 'transactions.payment_method_id')
                     ->where('transactions.payment_method_type', '=', 'Check');
            })
            ->leftJoin('payment_cash AS cash', function($join) {
                $join->on('cash.id', '=', 'transactions.payment_method_id')
                     ->where('transactions.payment_method_type', '=', 'Cash');
            })
            ->leftJoin('payment_credits AS credits', function($join) {
                $join->on('credits.id', '=', 'transactions.payment_method_id')
                     ->where('transactions.payment_method_type', '=', 'Credit');
            })
            ->leftJoin('users', 'users.id', '=', 'transactions.user_id')
            ->leftJoin('countries AS cards_country', 'cards_country.id', '=', 'cards.country_id')
            ->leftJoin('countries AS checks_country', 'checks_country.id', '=', 'checks.country_id')
            ->leftJoin('countries AS cash_country', 'cash_country.id', '=', 'cash.country_id')
            ->select(
                'transactions.id', 'amount', 'anonymous', 'user_id', 'payment_method_type', 'comment',
                'transactions.created_at', 'transactions.updated_at', 'auth', 'trans_id', 'cards.email',
                'reason', 'check_number', 'transactions.deleted_at', 'card_type', 'cc_number'
            )
            ->addSelect(DB::raw('
                CASE 
                    WHEN transactions.user_id IS NOT NULL THEN CONCAT(users.first_name, " ", users.last_name) 
                    WHEN cards.name IS NOT NULL THEN cards.name 
                    WHEN checks.name IS NOT NULL THEN checks.name
                    WHEN cash.name IS NOT NULL THEN cash.name
                    ELSE null 
                END AS donor
            '))
            ->addSelect(DB::raw('
                CASE 
                    WHEN cards.address IS NOT NULL THEN cards.address 
                    WHEN checks.address IS NOT NULL THEN checks.address
                    WHEN cash.address IS NOT NULL THEN cash.address
                    ELSE null 
                END AS address
            '))
            ->addSelect(DB::raw('
                CASE 
                    WHEN cards.city IS NOT NULL THEN cards.city 
                    WHEN checks.city IS NOT NULL THEN checks.city
                    WHEN cash.city IS NOT NULL THEN cash.city
                    ELSE null 
                END AS city
            '))
            ->addSelect(DB::raw('
                CASE 
                    WHEN cards.state IS NOT NULL THEN cards.state 
                    WHEN checks.state IS NOT NULL THEN checks.state
                    WHEN cash.state IS NOT NULL THEN cash.state
                    ELSE null 
                END AS state
            '))
            ->addSelect(DB::raw('
                CASE 
                    WHEN cards.zip IS NOT NULL THEN cards.zip 
                    WHEN checks.zip IS NOT NULL THEN checks.zip
                    WHEN cash.zip IS NOT NULL THEN cash.zip
                    ELSE null 
                END AS zip
            '))
            ->addSelect(DB::raw('
                CASE 
                    WHEN cards.country_id IS NOT NULL THEN cards_country.abbr 
                    WHEN checks.country_id IS NOT NULL THEN checks_country.abbr
                    WHEN cash.country_id IS NOT NULL THEN cash_country.abbr
                    ELSE null 
                END AS country_code
            '))
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
