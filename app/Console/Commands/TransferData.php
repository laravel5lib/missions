<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use App\Models\v1\Cost;
use App\Models\v1\Link;
use App\Models\v1\Note;
use App\Models\v1\Trip;
use App\Models\v1\User;
use App\Models\v1\Visa;
use App\Models\v1\Donor;
use App\Models\v1\Group;
use App\models\v1\Essay;
use App\Models\v1\Upload;
use App\Models\v1\Payment;
use App\Models\v1\Project;
use App\Models\v1\Campaign;
use App\Models\v1\Deadline;
use App\Models\v1\Passport;
use App\Models\v1\Referral;
use App\Models\v1\Companion;
use App\Models\v1\Fundraiser;
use App\Models\v1\Requirement;
use App\Models\v1\Reservation;
use App\Models\v1\Transaction;
use App\Utilities\v1\TeamRole;
use App\Models\v1\ProjectCause;
use Illuminate\Console\Command;
use App\Models\v1\MedicalRelease;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\v1\MedicalCondition;
use App\Models\v1\ProjectInitiative;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Artisan;
use App\Models\v1\ReservationRequirement;

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
            $this->callSilent('db:seed', [
                '--class' => 'UploadSeeder'
            ]);
            $this->info("\n" . 'Database re-migrated and seeded with defaults.');
        }

        if ($this->confirm('Do you want start importing data? [y|N]'))
        {
            // setup indexes to match 
            // old ids with new ones
            $userIndex = new Collection;
            $groupIndex = new Collection;
            $campaignIndex = new Collection;
            $tripIndex = new Collection;
            $requirementIndex = new Collection;
            $reservationIndex = new Collection;
            $fundraiserIndex = new Collection;
            $transactionIndex = new Collection;
            $passportIndex = new Collection;
            $visaIndex = new Collection;
            $referralIndex = new Collection;
            $essayIndex = new Collection;
            $medicalIndex = new Collection;
            $causeIndex = new Collection;
            $initiativeIndex = new Collection;
            $projectIndex = new Collection;
            $requirementIndex = new Collection;

            // STEP 1 - IMPORT USERS
            $this->import_users($userIndex);

            // STEP 2 - IMPORT GROUPS
            $this->import_groups($groupIndex, $userIndex);

            // STEP 3 - PASSPORTS
            $this->import_passports($passportIndex, $userIndex);

            // STEP 4 - VISAS
            $this->import_visas($visaIndex, $userIndex);

            // STEP 5- TESTIMONIES
            $this->import_essays($essayIndex, $userIndex);

            // STEP 6 - PASTORAL RECOMMENDATIONS
            $this->import_referrals($essayIndex, $userIndex);

            // STEP 7 - MEDICAL RELEASES
            $this->import_medical_releases($medicalIndex, $userIndex);

            // STEP 8 - CAMPAIGNS
            $this->import_campaigns($campaignIndex, $userIndex);

            // STEP 9 - TRIPS
            $this->import_trips($tripIndex, $campaignIndex, $groupIndex, $userIndex, $requirementIndex);
            
            // STEP 10 - RESERVATIONS
            $this->import_reservations($reservationIndex, $tripIndex, $userIndex);

            // STEP 11 - COMPANIONS
            $this->import_companions($reservationIndex);

            // STEP 12 - UPDATE RESERVATION REQUIREMENTS
            $this->update_reservation_requirements($reservationIndex, $passportIndex, $visaIndex, $referralIndex, $medicalIndex, $essayIndex, $requirementIndex);

            // STEP 13 - CAUSES
            $this->import_causes($causeIndex, $initiativeIndex);

            // STEP 14 - PROJECTS
            $this->import_projects($initiativeIndex, $userIndex, $groupIndex);
        }
    }

    /**
     * Import users
     * 
     * @param  Collection $userIndex
     * @return void 
     */
    private function import_users($userIndex)
    {
        $this->line('Importing users...');
        $users = $this->users();
        $bar = $this->output->createProgressBar($users->count());

        $newUsers = $users->map(function($item) use($userIndex, $bar) {
            $banner = Upload::randomBanner()->first();
            $user = [
                'email' => $item->email,
                'name' => ucwords($item->name),
                'password' => $item->password,
                'gender' => strtolower($item->gender),
                'status' => $item->status <> '' ? strtolower($item->status) : 'single',
                'shirt_size' => strtolower($item->shirt_size),
                'height' => convert_to_cm($item->height_ft, $item->height_in),
                'weight' => convert_to_kg($item->weight),
                'birthday' => $item->dob,
                'phone_one' => $item->cell_phone,
                'phone_two' => $item->home_phone,
                'address' => $item->address,
                'city' => $item->city,
                'state' => $item->state,
                'zip' => $item->zip,
                'country_code' => strtolower($item->country_code),
                'timezone' => $item->timezone == 'US/Eastern' ? 'America/Detroit' : $item->timezone,
                'bio' => $item->bio,
                'public' => $item->public ? true : false,
                'avatar_upload_id' => trim($item->profile_pic_src) ? 
                    $this->get_avatar_id($item->profile_pic_src, $item->name) : null,
                'banner_upload_id' => $banner ? $banner->id : null,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at
            ];

            $newUser = User::create($user);
            
            $item->admin > 0 ? $newUser->assign('admin') : $newUser->assign('member');
            
            $url = $item->url ? $item->url : generate_slug($newUser->name);
            $newUser->slug()->create(['url' => $url]);

            $userIndex->put($item->id, $newUser->id);
            
            $links = $this->get_links($item)->map(function($link) {
                 return new Link([
                     'name' => $link['name'], 'url' => $link['url']
                 ]);
            })->all();
            $newUser->links()->saveMany($links);

            $bar->advance();
  
        })->all();
        $bar->finish();
        $this->info("\n" . 'Users imported.');
    }

    /**
     * Import Groups
     * 
     * @param  Collection $groupIndex
     * @param  Collection $userIndex
     * @return void
     */
    private function import_groups($groupIndex, $userIndex)
    {
        $this->line("\n" . 'Importing groups...');
        $groups = $this->groups();
        $bar = $this->output->createProgressBar($groups->count());
        $groups->map(function($item) use($bar, $groupIndex, $userIndex) {
            $group = Group::create([
                'name' => ucwords($item->name),
                'type' => str_replace('-', '', strtolower($item->type)),
                'description' => trim($item->description),
                'public' => $item->public ? true : false,
                'timezone' => $item->timezone,
                'address_one' => $item->address_one,
                'city' => $item->city,
                'state' => $item->state,
                'zip' => $item->zip,
                'country_code' => strtolower($item->country_code),
                'phone_one' => $item->phone_one,
                'email' => $item->email,
                'status' => 'approved',
                'avatar_upload_id' => trim($item->logo_src) ? 
                    $this->get_avatar_id($item->logo_src, $item->name) : null,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at
            ]);
               
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
           if ($item->manager_ids) {
                $managers = collect(explode(',', $item->manager_ids))
                    ->map(function($manager_id) use($userIndex) {
                        return $userIndex->get($manager_id);
                    })->flatten()->all();
                $group->managers()->attach(implode(',', $managers));
           }

            $groupIndex->put($item->id, $group->id);

            $bar->advance();
        });
        $bar->finish();
        $this->info("\n" . 'Groups imported.');
    }

    /**
     * Import Passports
     * 
     * @param  Collection $passportIndex
     * @param  Collection $userIndex
     * @return void
     */
    private function import_passports($passportIndex, $userIndex)
    {
        $this->line('Importing passports ...');
        $oldPassports = $this->passports();
        $bar = $this->output->createProgressBar($oldPassports->count());
        $oldPassports->map(function($passport) use($bar, $passportIndex, $userIndex) {
            $pass = new Passport([
                'user_id' => $userIndex->get($passport->user_id),
                'given_names' => $passport->given_names,
                'surname' => $passport->surname,
                'number' => $passport->number,
                'birth_country' => strtolower($passport->birth_country),
                'citizenship' => strtolower($passport->citizenship),
                'upload_id' => $passport->image_scan ? $this->get_upload_id($passport->image_scan, 'passport-'.$passport->given_names.'-'.$passport->surname) : null,
                'expires_at' => $passport->expiration,
                'created_at' => $passport->created_at,
                'updated_at' => $passport->updated_at
            ]);
            $pass->save();
            $passportIndex->put($passport->id, $pass->id);
            $bar->advance();
        });
        $bar->finish();
        $this->info("\n" . 'Passports imported.' . "\n");
    }

    /**
     * Import visas
     * 
     * @param  Collection $visaIndex
     * @param  Collection $userIndex
     * @return void
     */
    private function import_visas($visaIndex, $userIndex)
    {
        $this->line('Importing visas ...');
        $oldVisas = $this->visas();
        $bar = $this->output->createProgressBar($oldVisas->count());
        $oldVisas->map(function($visa) use($bar, $visaIndex, $userIndex) {
            $newVisa = new Visa([
                'user_id' => $userIndex->get($visa->user_id),
                'given_names' => $visa->given_names,
                'surname' => $visa->surname,
                'number' => $visa->number,
                'country_code' => 'in',
                'upload_id' => $visa->image ? $this->get_upload_id($visa->image, 'visa-'.$visa->given_names.'-'.$visa->surname) : null,
                'issued_at' => $visa->issued,
                'expires_at' => $visa->expiration,
                'created_at' => $visa->created_at,
                'updated_at' => $visa->updated_at
            ]);
            $newVisa->save();
            $visaIndex->put($visa->id, $newVisa->id);
            $bar->advance();
        });
        $bar->finish();
        $this->info("\n" . 'Visas imported.' . "\n");
    }

    /**
     * Import Essays
     * 
     * @param  Collection $essayIndex
     * @param  Collection $userIndex
     * @return Void
     */
    private function import_essays($essayIndex, $userIndex)
    {
        $this->line('Importing testimonies ...');
        $testimonies = $this->testimonies();
        $bar = $this->output->createProgressBar($testimonies->count());
        $testimonies->map(function($testimony) use($bar, $essayIndex, $userIndex) {
            $essay = Essay::create([
                'user_id' => $userIndex->get($testimony->user_id),
                'author_name' => ucwords(strtolower($testimony->name)),
                'subject' => 'Testimony',
                'content' => [
                    [
                        'q' => 'Describe how you decided to follow Christ',
                        'a' => strip_tags(trim($testimony->decision))
                    ],
                    [
                        'q' => 'Describe your church involvement',
                        'a' => strip_tags(trim($testimony->church))
                    ],
                    [
                        'q' => 'Describe your current walk with God',
                        'a' => strip_tags(trim($testimony->walk))
                    ],
                    [
                        'q' => 'Please describe any prior missions trip experience you have',
                        'a' => strip_tags(trim($testimony->missions_experience))
                    ]
                ],
                'created_at' => $testimony->created_at,
                'updated_at' => $testimony->updated_at
            ]);
            $essayIndex->put($testimony->id, $essay->id);
            $bar->advance();
        });
        $bar->finish();
        $this->info("\n" . 'Testimonies imported.' . "\n");
    }

    /**
     * Import referrals
     * 
     * @param  Collection $referralIndex
     * @param  Collection $userIndex
     * @return void
     */
    private function import_referrals($referralIndex, $userIndex)
    {
        $this->line('Importing pastoral recommendations...');
        $recommendations = $this->recommendations();
        $bar = $this->output->createProgressBar($recommendations->count());
        $recommendations->map(function($rec) use($bar, $referralIndex, $userIndex) {
            $referral = Referral::create([
                'user_id' => $userIndex->get($rec->user_id),
                'applicant_name' => $rec->user_name,
                'type' => 'pastoral',
                'attention_to' => $rec->name,
                'recipient_email' => $rec->email,
                'referrer' => [
                    'title' => $rec->offical_title,
                    'name' => $rec->name,
                    'organization' => $rec->org_name,
                    'phone' => $rec->phone,
                    'address' => $rec->address,
                    'city' => $rec->city,
                    'state' => $rec->state,
                    'zip' => $rec->zip,
                    'country' => $rec->country
                ],
                'response' => [
                    [
                        'q' => 'How long have you known the applicant?',
                        'a' => strip_tags(trim($rec->known_applicant))
                    ],
                    [
                        'q' => 'Please list any current roles the applicant serves in at your church:',
                        'a' => strip_tags(trim($rec->applicant_roles))
                    ],
                    [
                        'q' => 'To the best of your knowledge, what is the current state of the applicant\'s spiritual walk?',
                        'a' => strip_tags(trim($rec->applicant_walk))
                    ],
                    [
                        'q' => 'Do you have any concerns about this applicant\'s spiritual, physical, and social endurance in a foreign nation for 7-14 days? Please explain.',
                        'a' => strip_tags(trim($rec->concerns))
                    ],
                    [
                        'q' => 'Please list the applicant\'s significant strengths:',
                        'a' => strip_tags(trim($rec->applicant_strength))
                    ],
                    [
                        'q' => 'Please list the applicant\'s significant weaknesses:',
                        'a' => strip_tags(trim($rec->applicant_weakness))
                    ],
                    [
                        'q' => 'Would you recommend this applicant for a leadership role on the trip? Please explain.',
                        'a' => strip_tags(trim($rec->leadership_recommendation))
                    ]
                ],
                'sent_at' => $rec->created_at,
                'responded_at' => $rec->responded_at,
                'created_at' => $rec->created_at,
                'updated_at' => $rec->updated_at
            ]);
            $referralIndex->put($rec->id, $referral->id);
            $bar->advance();
        });
        $bar->finish();
        $this->info("\n" . 'Pastoral recommendations imported.' . "\n");
    }

    /**
     * Import medical releases
     * 
     * @param  Collection $medicalIndex
     * @param  Collection $userIndex 
     * @return void
     */
    private function import_medical_releases($medicalIndex, $userIndex)
    {
        $this->line('Importing medical releases...');
        $medicalReleases = $this->medicalReleases();
        $bar = $this->output->createProgressBar($medicalReleases->count());
        $medicalReleases->map(function($release) use($bar, $medicalIndex, $userIndex) {
            $medical = MedicalRelease::create([
                'user_id' => $userIndex->get($release->user_id),
                'name' => ucwords(strtolower($release->name)),
                'ins_provider' => empty($release->provider) ? null : ucwords(strtolower($release->provider)),
                'ins_policy_no' => empty($release->policy_number) ? null : strtoupper($release->policy_number),
                'emergency_contact' => [
                    'name' => ucwords(strtolower($release->emergency_name)),
                    'email' => strtolower($release->emergency_email),
                    'phone' => $release->emergency_phone
                ],
                'is_risk' => false,
                'created_at' => $release->created_at,
                'updated_at' => $release->updated_at
            ]);
            $conditions = $this->conditions($release->id)->map(function($condition) {
                return new MedicalCondition([
                    'name' => $condition->name,
                    'medication' => false,
                    'diagnosed' => true
                ]);
            })->all();
            $medical->conditions()->saveMany($conditions);
            $notes = collect([
                'other' => $release->other, 
                'allergies' => $release->allergies, 
                'medications' => $release->medications
            ])->reject(function($item) {
                $string = trim(strtolower(preg_replace('[^A-Za-z]', '', $item)));
                return in_array($string, [null, '', 'none', 'na', 'n/a', 'na/a', 'n/a.', 'n/s', 0, '0', '-', 'n.a.', 'nada', 'nil', 'nhone', 'nka', 'nkda', 'nope', 'npne', 'n\a', 'n /a', 'n.a', 'nine', 'nione'], true);
            })->reject(function($item) {
                return starts_with(strtolower($item), 'no') || starts_with(strtolower($item), 'nil') || starts_with(strtolower($item), 'n/a') || starts_with(strtolower($item), 'nka') || starts_with(strtolower($item), 'nkda') || empty($item) || $item === '';
            })->map(function($value, $key) use($medical) {
                return new Note([
                    'subject' => $key == 'other' ? 'Other Concerns' : ucwords($key),
                    'content' => ucfirst(strtolower($value)),
                    'user_id' => $medical->user_id
                ]);
            })->all();
            $medical->notes()->saveMany($notes);
            $medicalIndex->put($release->id, $medical->id);
            $bar->advance();
        });
        $bar->finish();
        $this->info("\n" . 'Medical releases imported.' . "\n");
    }

    /**
     * Import campaigns
     * 
     * @param  Collection $campaignIndex
     * @return void
     */
    private function import_campaigns($campaignIndex, $userIndex)
    {
        $campaigns = $this->campaigns();
        $bar = $this->output->createProgressBar($campaigns->count());
        $this->line('Importing Campaigns...' ."\n");
        $campaigns->map(function($item) use($bar, $campaignIndex, $userIndex) {
            $campaign = Campaign::create([
                'name' => $item->name,
                'country_code' => strtolower($item->country_code),
                'short_desc' => null,
                'page_src' => null,
                'avatar_upload_id' => trim($item->image) ? 
                    $this->get_avatar_id($item->image, $item->name) : null,
                'banner_upload_id' => null,
                'started_at' => $item->start_date,
                'ended_at' => $item->end_date,
                'published_at' => $item->public ? $item->created_at : null,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at
            ]);
            $campaignIndex->put($item->id, $campaign->id);
            // set slug
            $url = $item->slug ? $item->slug : generate_slug($campaign->name);
            $campaign->slug()->create(['url' => $url]);

            $transactions = $this->get_transactions($item->id, 'Campaign', $userIndex);
            $campaign->fund->transactions()->saveMany($transactions);
            $campaign->fund->reconcile();

            $bar->advance();
        });
        $bar->finish();
        $this->info("\n" . 'Campaigns imported.' . "\n");
    }

    /**
     * Import trips
     * 
     * @param  Collection $tripIndex     
     * @param  Collection $campaignIndex 
     * @param  Collection $groupIndex    
     * @param  Collection $userIndex     
     * @return void
     */
    private function import_trips($tripIndex, $campaignIndex, $groupIndex, $userIndex, $requirementIndex)
    {
        $this->line("\n" . 'Importing trips...');
        $trips = $this->trips();
        $bar = $this->output->createProgressBar($trips->count());
        $trips->map(function($item) use($bar, $tripIndex, $campaignIndex, $groupIndex, $userIndex, $requirementIndex) {
            $campaign_id = $campaignIndex->get($item->campaign_id);
            $rep_id = $item->rep_id ? $userIndex->get($item->rep_id) : null;
            $country_code = Campaign::where('id', $campaign_id)->pluck('country_code')->first();
            $trip = Trip::create([
                'campaign_id' => $campaign_id,
                'group_id' => $groupIndex->get($item->group_id),
                'spots' => $item->spots,
                'companion_limit' => $item->companion_limit,
                'type' => $this->get_trip_type($item->type),
                'country_code' => $country_code,
                'difficulty' => 'level_'.$item->difficulty_id,
                'started_at' => $item->start_date,
                'ended_at' => $item->end_date,
                'rep_id' => $rep_id,
                'todos' => [
                    'Send shirt',
                    'Send wrist band',
                    'Enter into LGL',
                    'Send launch guide',
                    'Send luggage tag'
                ],
                'prospects' => $item->prospects ? explode(',', $item->prospects) : null,
                'team_roles' => $this->get_team_role_codes($item->team_roles),
                'description' => '### What to Expect'."\n\n".trim(strip_tags($item->what_to_expect))."\n\n".'### What\'s Included in my Trip Registration?'."\n\n".trim(strip_tags($item->included))."\n\n".'### What\'s not Included in my Trip Registration?'."\n\n".trim(strip_tags($item->not_included))."\n\n".'### Pre-trip Training'."\n\n".trim(strip_tags($item->training))."\n\n".'### How You\'ll Get There'."\n\n".trim(strip_tags($item->flight_information)),
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
            $this->add_trip_costs($item, $trip);
            $this->add_trip_requirements($item, $trip, $requirementIndex);
            $this->add_trip_deadlines($item, $trip);
            $tripIndex->put($item->id, $trip->id);
            $bar->advance();
        })->all();
        $bar->finish();
        $this->info("\n" . 'Trips imported.');
    }

    /**
     * Import reservations
     * 
     * @param  Collection $reservationIndex
     * @param  Collection $tripIndex       
     * @param  Collection $userIndex       
     * @return void
     */
    private function import_reservations($reservationIndex, $tripIndex, $userIndex)
    {
        $this->line("\n" . 'Importing reservations...');
        // grab reservations from old sys and map to new format
        $reservations = $this->reservations();
        $bar = $this->output->createProgressBar($reservations->count());
        $reservations->map(function($r) use($bar, $reservationIndex, $tripIndex, $userIndex) {
            $user_id = $userIndex->get($r->user_id);
            $rep_id = $r->rep_id ? $userIndex->get($r->rep_id) : null;
            $trip_id = $tripIndex->get($r->trip_id);

            $user = User::findOrFail($user_id);
            $trip = Trip::findOrFail($trip_id);

            $reservation = new Reservation([
                'trip_id' => $trip->id,
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
                'desired_role' => $r->role ? $this->get_team_role_codes($r->role)[0] : null,
                'user_id' => $user->id,
                'rep_id' => $rep_id,
                'avatar_upload_id' => $user->avatar_upload_id,
                'companion_limit' => $trip->companion_limit,
                'created_at' => $r->created_at,
                'updated_at' => $r->updated_at,
                'deleted_at' => $r->dropped ? Carbon::now() : null
            ]);
            $reservation->save();
            // push to ID index
            $reservationIndex->put($r->id, $reservation->id);
            // generate a fund for the reservation
            $fundName = generateFundName($reservation);
            $fund = $reservation->fund()->create([
                'name' => $fundName,
                'slug' => generate_fund_slug($fundName),
                'balance' => 0,
                'class' => generateQbClassName($reservation),
                'item' => 'Missionary Donation'
            ]);
            // apply trip costs to the reservation
            $costs = $this->get_reservation_costs($r, $trip);
            $reservation->costs()->attach($costs);
            // add transactions and update balances
            $transactions = $this->get_transactions($r->id, 'Reservation', $userIndex);
            $fund->transactions()->saveMany($transactions);
            $fund->reconcile();
            $reservation->payments()->sync();
            // create a fundraiser for the reservation's fund
            $fundraiser = $fund->fundraisers()->save(new Fundraiser([
                'name' => generateFundraiserName($reservation),
                'url' => $r->slug ?: generate_fundraiser_slug(
                    country($trip->country_code).'-'.$trip->started_at->format('Y')
                ),
                'description' => strip_tags($r->support_description) ?: file_get_contents(resource_path('assets/sample_fundraiser.md')),
                'sponsor_type' => 'users',
                'sponsor_id' => $reservation->user_id,
                'goal_amount' => $reservation->getTotalCost()/100,
                'started_at' => $reservation->created_at,
                'ended_at' => $trip->started_at,
                'public' => $r->public ? true : false,
            ]));
            // add video to fundraiser
            if($r->video_type && $r->video) {
                $fundraiser->uploads()->create([
                    'name' => strtolower($r->video_type).'_'.time(),
                    'type' => 'video',
                    'source' => $r->video_type == 'YouTube' ? '//www.youtube.com/watch?'.$r->video : '//vimeo.com/'.$r->video,
                    'meta' => ['format' => strtolower($r->video_type)]
                ]);
            }
            // add photos if they exist
            if($r->photos) {
                $this->add_uploads_to_fundraiser($fundraiser, $r->photos);
            }
            // add requirements, deadlines, and todos
            $reservation->syncRequirements($trip->requirements);
            $reservation->syncDeadlines($trip->deadlines);
            $reservation->addTodos($trip->todos);
            if($r->notes && ($reservation->rep_id ?: $reservation->trip->rep_id)) {
                $reservation->notes()->create([
                    'subject' => 'Imported Notes',
                    'content' => $r->notes,
                    'user_id' => $reservation->rep_id ?: $reservation->trip->rep_id
                ]);
            }
            $bar->advance();
        });
        $bar->finish();
        $this->info("\n" . 'Reservations imported.');
    }

    /**
     * Import companions
     * 
     * @param  Collection $reservationIndex
     * @return void
     */
    private function import_companions($reservationIndex)
    {
        $companions = $this->companions();
        $this->line('Importing travel companions...' ."\n");
        $bar = $this->output->createProgressBar($companions->count());
        $companions->map(function($comp) use($bar, $reservationIndex) {
            $companion = Companion::create([
                'reservation_id' => $reservationIndex->get($comp->reservation_id),
                'companion_id' => $reservationIndex->get($comp->co_reservation_id),
                'group_key' => $comp->group_key,
                'relationship' => strtolower($comp->relationship)
            ]);
            $bar->advance();
            return $companion;
        })->all();
        $bar->finish();
        $this->info("\n" . 'Companions imported.');
    }

    private function update_reservation_requirements($reservationIndex, $passportIndex, $visaIndex, $referralIndex, $medicalIndex, $essayIndex, $requirementIndex)
    {
        $this->line("\n" . 'Updating reservation requirements...' . "\n");

        $this->reservation_requirements()->map(function($req) use($reservationIndex, $passportIndex, $visaIndex, $referralIndex, $medicalIndex, $essayIndex, $requirementIndex) {
            $doc_type = $this->get_document_type($req->formable_type);
            $doc_id = null;

            if ($req->formable_id) {
                switch ($doc_type) {
                    case 'passports':
                        $doc_id = $passportIndex->get($req->formable_id);
                        break;
                    case 'visas':
                        $doc_id = $visaIndex->get($req->formable_id);
                        break;
                    case 'referrals':
                        $doc_id = $referralIndex->get($req->formable_id);
                        break;
                    case 'medical_releases':
                        $doc_id = $medicalIndex->get($req->formable_id);
                        break;
                    case 'essays':
                        $doc_id = $essayIndex->get($req->formable_id);
                        break;
                    default:
                        $doc_id = null;
                        break;
                }
            }

            $requirement = ReservationRequirement::where('reservation_id', '=', $reservationIndex->get($req->reservation_id))
                ->where('requirement_id', '=', $requirementIndex->get($req->requireable_id))
                ->update([
                    'status' => $req->status == 2 ? 'reviewing' : ($req->status == 1 ? 'complete' : 'incomplete'),
                    'document_id' => $doc_id
                ]);
        });
        $this->info("\n" . 'Reservation requirements updated.');
    }

    private function import_causes($causeIndex, $initiativeIndex)
    {
        $this->line('Importing causes and initiatives...' ."\n");
        $causes = $this->causes();
        $bar = $this->output->createProgressBar($causes->count());
        $causes->map(function($c) use($bar, $causeIndex, $initiativeIndex) {
            $cause = ProjectCause::create([
                'name' => $c->name,
                'countries' => $this->get_countries($c->countries),
                'short_desc' => $c->short_desc,
                'upload_id' => $c->thumb_src ? $this->get_avatar_id($c->thumb_src, $c->name) : null,
                'short_desc' => $c->short_desc,
                'created_at' => $c->created_at,
                'updated_at' => $c->updated_at
            ]);
            $causeIndex->put($c->id, $cause->id);
            
            $this->initiatives($c->id)->map(function($i) use($cause, $initiativeIndex) {
                $initiative = $cause->initiatives()->create([
                    'type' => $i->name,
                    'country_code' => strtolower($i->country_code),
                    'upload_id' => $i->thumb_src ? $this->get_avatar_id($i->thumb_src, $i->name) : null,
                    'short_desc' => $i->short_desc,
                    'created_at' => $i->created_at,
                    'started_at' => $i->started_at,
                    'ended_at' => $i->ended_at,
                    'updated_at' => $i->updated_at,
                    'deleted_at' => null
                ]);
                $initiativeIndex->put($i->id, $initiative->id);

                // add available deadlines
                if($i->launch_dates) {
                    $deadlines = collect(explode(',', $i->launch_dates))->map(function($date) {
                        return new Deadline([
                            'name' => 'Launch Date',
                            'date' => $date,
                            'grace_period' => 0,
                            'enforced' => false
                        ]);
                    });
                    $initiative->deadlines()->saveMany($deadlines);
                }
            });
            $bar->advance();
        });
        $bar->finish();
        $this->info("\n" . 'Causes and initiatives imported.');
    }

    private function get_countries($list)
    {
        $countries = collect(explode(',', strtolower($list)));

        return $countries->transform(function($country) {
            return [
                'code' => $country,
                'name' => country($country)
            ];
        })->all();
    }

    private function import_projects($initiativeIndex, $userIndex, $groupIndex)
    {
        $this->line('Importing projects...' ."\n");
        $projects = $this->projects();
        $bar = $this->output->createProgressBar($projects->count());
        $projects->map(function($proj) use($bar, $initiativeIndex, $userIndex, $groupIndex) {
            $project = new Project([
                'name' => trim($proj->name),
                'project_initiative_id' => $initiativeIndex->get($proj->initiative_id),
                'plaque_prefix' => $proj->prefix,
                'plaque_message' => $proj->plaque_msg,
                'funded_at' => null,
                'created_at' => $proj->created_at,
                'updated_at' => $proj->updated_at,
                'deleted_at' => $proj->deleted_at
            ]);

            if($proj->sponsorable_type == 'Group') {
                $project->sponsor_id = $groupIndex->get($proj->sponsorable_id);
                $project->sponsor_type = 'groups';
            } else {
                $project->sponsor_id = $userIndex->get($proj->sponsorable_id);
                $project->sponsor_type = 'users';
            }

            $project->save();
            // add costs & payments
            $cost = $project->costs()->create([
                'name' => 'Standard Project Cost',
                'description' => null,
                'amount' => $proj->amount,
                'type' => 'Static',
                'active_at' => Carbon::parse($proj->launch_date)->subYear()
            ]);
            $cost->payments()->saveMany([
                new Payment([
                    'amount_owed' => $proj->amount/2,
                    'percent_owed' => 50,
                    'due_at' => Carbon::parse($proj->launch_date)->subdays($proj->half_due_days)
                ]),
                new Payment([
                    'amount_owed' => $proj->amount/2,
                    'percent_owed' => 50,
                    'due_at' => Carbon::parse($proj->launch_date)->subdays($proj->full_due_days)
                ])
            ]);
            $this->project_optional_costs($proj->id)->map(function($c) use($project, $proj) {
                $cost = $project->costs()->create([
                    'name' => $c->name,
                    'description' => $c->short_desc,
                    'amount' => $c->amount,
                    'type' => 'optional',
                    'active_at' => $proj->created_at
                ]);
                $cost->payments()->saveMany([
                    new Payment([
                        'amount_owed' => $c->amount/2,
                        'percent_owed' => 50,
                        'due_at' => Carbon::parse($proj->launch_date)->subdays($proj->half_due_days)
                    ]),
                    new Payment([
                        'amount_owed' => $c->amount/2,
                        'percent_owed' => 50,
                        'due_at' => Carbon::parse($proj->launch_date)->subdays($proj->full_due_days)
                    ])
                ]);
            });
            // add deadlines

            $transactions = $this->get_transactions($proj->id, 'SponsoredProject', $userIndex);
            $fund = $project->fund;

            $fund->transactions()->saveMany($transactions);
            $fund->reconcile();
            $project->payments()->sync();
            // create a fundraiser for the project's fund
            $fundraiser_name = $proj->fundraiser_name ?: generateFundraiserName($project);
            $fundraiser = $fund->fundraisers()->save(new Fundraiser([
                'name' => trim($fundraiser_name),
                'url' => $proj->slug ?: generate_fundraiser_slug($fundraiser_name),
                'description' => strip_tags($proj->support_msg) ?: file_get_contents(resource_path('assets/sample_fundraiser.md')),
                'sponsor_type' => $project->sponsor_type,
                'sponsor_id' => $project->sponsor_id,
                'goal_amount' => $project->goal/100,
                'started_at' => $project->created_at,
                'ended_at' => $proj->deadline,
                'public' => $proj->public ? true : false,
                'created_at' => $proj->fundraiser_created ?: Carbon::now(),
                'updated_at' => $proj->fundraiser_updated ?: Carbon::now(),
                'deleted_at' => $proj->fundraiser_deleted ?: Carbon::now()
            ]));
            // add video to fundraiser
            if($proj->video_type && $proj->video_id) {
                $fundraiser->uploads()->create([
                    'name' => strtolower($proj->video_type).'_'.time(),
                    'type' => 'video',
                    'source' => $proj->video_type == 'YouTube' ? '//www.youtube.com/watch?'.$proj->video_id : '//vimeo.com/'.$proj->video_id,
                    'meta' => ['format' => strtolower($proj->video_type)]
                ]);
            }
            // add photos if they exist
            if($proj->photos) {
                $this->add_uploads_to_fundraiser($fundraiser, $proj->photos);
            }

            $bar->advance();
        });
        $bar->finish();
        $this->info("\n" . 'Projects imported.');
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
    private function get_transactions($id, $type, $userIndex)
    {
        // get old sys transactions, map to new format and save
        return $this->transactions($id, $type)->map(function($trans) use($userIndex) {
            $user_id = $userIndex->get($trans->user_id);
            $transaction = new Transaction([
                'amount' => $trans->amount,
                'type' => $trans->payment_method_type == 'Credit' ? 'credit' : 'donation',
                'details' => $this->get_transaction_details($trans),
                'donor_id' => $trans->donor ? $this->get_donor_id($trans, $user_id) : null,
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

    private function get_donor_id($trans, $user_id = null) {
        if ($user_id) {
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
    private function add_trip_requirements($item, $trip, $requirementIndex)
    {
        $requirements = $this->trip_requirements($item->id)
            ->reject(function($requirement) {
                return is_null($this->get_document_type($requirement->formable));
            })
            ->map(function($requirement) use($item, $trip, $requirementIndex) {
                $req = $trip->requirements()->create([
                    'name' => $requirement->name,
                    'short_desc' => $requirement->description,
                    'document_type' => $this->get_document_type($requirement->formable),
                    'due_at' => $item->requirements ?: Carbon::parse($item->start_date)->subDays(82),
                    'grace_period' => 3
                ]);
                $requirementIndex->put($requirement->id, $req->id);
            });
    }

    /**
     * Get the document type
     * @param  String $type
     * @return String
     */
    private function get_document_type($type)
    {
        switch ($type) {
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
    private function get_new_or_existing_group($item, $groupIndex, $userIndex)
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
               if ($item->manager_ids) {
                    $managers = collect(explode(',', $item->manager_ids))->map(function($manager_id) use($userIndex) {
                        return $userIndex->get($manager_id);
                    })->all();
                    $group->managers()->sync($managers);
               }

                $groupIndex->push([$item->id => $group->id]);
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

    private function get_upload_id($source, $name)
    {
        $upload = Upload::firstOrNew([
            'source' => 'images/other/'.$source
        ]);

        if (! $upload->id) {
            $upload->name = str_slug($name);
            $upload->type = 'other';
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
    private function get_new_or_existing_user($item, $userIndex)
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
            $userIndex->push([$item->id => $user->id]);
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
                'public', 'start_date', 'end_date', 'image'
            )
            ->get());
    }

    /**
     * Get trips
     * 
     * @param Integer $campaign_id
     * @return Collection 
     */
    private function trips()
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
            ->leftJoin('reps', 'reps.id', '=', 'trips.rep_id')
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
                'trip_deadlines.companions',
                'trips.campaign_id',
                'reps.user_id AS rep_id',
                'trip_pages.what_to_expect',
                'trip_pages.included',
                'trip_pages.not_included',
                'trip_pages.training',
                'trip_pages.flight_information'
            )->addSelect(
               DB::raw('GROUP_CONCAT(DISTINCT travelers.traveler) AS prospects')
            )->addSelect(
               DB::raw('GROUP_CONCAT(DISTINCT roles.name) AS team_roles'))
            // )->addSelect(
            //    DB::raw("CONCAT('###WHAT TO EXPECT', '

            //     ', trip_pages.what_to_expect, '

            //     ', '### What\'s Included in my Trip Registration?', '

            //     ', trip_pages.included, '

            //     ', '### What\'s not Included in my Trip Registration?', '

            //     ', trip_pages.not_included, '

            //     ', '### Pre-trip Training', '

            //     ', trip_pages.training, '

            //     ', '### How You Will Get There', '

            //     ', trip_pages.flight_information) AS description")
            // )
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
        return collect($this->connection()->table('users')
            ->leftJoin('timezones', 'users.timezone_id', '=', 'timezones.id')
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
            ->groupBy('users.id')
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
            "))->get());
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
            ->select('name', 'description', 'formable', 'requireables.id')
            ->get());
    }

    private function reservation_requirements()
    {
        return collect($this->connection()
            ->table('requireable_reservation AS rr')
            ->select(
                'rr.reservation_id', 'rr.formable_type', 'rr.formable_id', 'rr.status', 
                'rr.requireable_id'
            )
            ->get());
    }

    /**
     * Get reservations
     * 
     * @param  Integer $trip_id 
     * @return Collection          
     */
    private function reservations()
    {
        return collect($this->connection()
            ->table('reservations')
            ->leftJoin('reservation_pages', 'reservation_pages.reservation_id', '=', 'reservations.id')
            ->join('users', 'users.id', '=', 'reservations.user_id')
            ->join('roles', 'roles.id', '=', 'reservations.role_id')
            ->leftJoin('contacts', 'contacts.user_id', '=', 'users.id')
            ->leftJoin('uploads', function($join) {
                $join->on('reservation_pages.id', '=', 'uploads.uploadable_id')
                     ->where('uploads.uploadable_type', '=', 'ReservationPage');
            })
            ->leftJoin('reps', 'reps.id', '=', 'reservations.rep_id')
            ->select(
                'first_name', 'middle_name', 'last_name', 'reservations.user_id', 'roles.name AS role',
                'dropped', 'shirt_sent', 'launch_guide_sent', 'luggage_tag_sent',
                'sent_to_lgl', 'reps.user_id AS rep_id', 'reservations.created_at', 'reservations.updated_at',
                'support_description', 'reservation_pages.slug', 'reservation_pages.public',
                'reservations.status_id AS payment_status', 'reservations.id',
                'reservation_pages.video', 'reservation_pages.video_type', 'notes', 'reservations.trip_id'
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
     * Get Passports
     * 
     * @return Collection
     */
    private function passports()
    {
        return collect($this->connection()
            ->table('passports')
            ->join('countries AS nationality', 'nationality.id', '=', 'passports.birth_country_id')
            ->join('countries AS citizenship', 'citizenship.id', '=', 'passports.citizenship_country_id')
            ->select(
                'given_names', 'surname', 'number', 'expiration', 'nationality.abbr AS birth_country',
                'citizenship.abbr AS citizenship', 'image_scan', 'passports.created_at', 'passports.updated_at',
                'user_id', 'passports.id'
            )
            ->get());
    }

    private function visas()
    {
        return collect($this->connection()
            ->table('visas')
            ->join('users', 'visas.user_id', '=', 'users.id')
            ->select(
                'number', 'expiration', 'issued', 'image', 
                'visas.created_at', 'visas.updated_at', 'user_id', 'visas.id',
                'first_name AS given_names', 'last_name AS surname'
            )
            ->get());
    }

    /**
     * Get testimonies
     * 
     * @return Collection
     */
    private function testimonies()
    {
        return collect($this->connection()
            ->table('testimonies')
            ->join('users', 'users.id', '=', 'testimonies.id')
            ->select(
                'user_id', 'decision', 'church', 'walk', 'missions_experience', 
                'testimonies.created_at', 'testimonies.updated_at', 'testimonies.id'
            )
            ->addSelect(DB::raw('CONCAT(users.first_name, " ", users.last_name) AS name'))
            ->get());
    }

    /**
     * Get recommendations.
     * 
     * @return Collection
     */
    private function recommendations()
    {
        return collect($this->connection()
            ->table('recommendation_requests AS requests')
            ->join('recommendation_responses AS responses', 'responses.request_id', '=', 'requests.id')
            ->join('users', 'requests.user_id', '=', 'users.id')
            ->join('countries', 'requests.country_id', '=', 'countries.id')
            ->select(
                'requests.name', 'org_name', 'address', 'city', 'state', 'zip', 'countries.name AS country', 'phone',
                'requests.email', 'requests.created_at', 'requests.updated_at', 'responses.created_at AS responded_at',
                'offical_title', 'known_applicant', 'applicant_roles', 'applicant_walk', 'concerns',
                'applicant_strength', 'applicant_weakness', 'leadership_recommendation', 'user_id',
                'requests.id'
            )
            ->addSelect(DB::raw('CONCAT(users.first_name, " ", users.last_name) AS user_name'))
            ->get());
    }

    private function medicalReleases()
    {
        return collect($this->connection()
            ->table('medical_releases')
            ->join('users', 'medical_releases.user_id', '=', 'users.id')
            ->select(
                'provider', 'policy_number', 'emergency_name', 'emergency_email',
                'emergency_phone', 'other', 'allergies', 'medications', 'medical_releases.created_at',
                'medical_releases.updated_at', 'user_id', 'medical_releases.id'
            )
            ->addSelect(DB::raw('CONCAT(users.first_name, " ", users.last_name) AS name'))
            ->get());
    }

    private function conditions($id)
    {
        return collect($this->connection()
            ->table('medical_release_condition')
            ->where('release_id', $id)
            ->join('conditions', 'medical_release_condition.condition_id', '=', 'conditions.id')
            ->select('name')
            ->get());
    }

    private function companions()
    {
        return collect($this->connection()
            ->table('companions')
            ->join('relationships', 'companions.relationship_id', '=', 'relationships.id')
            ->select('name AS relationship', 'reservation_id', 'co_reservation_id', 'group_key')
            ->get());
    }

    private function causes()
    {
        return collect($this->connection()
            ->table('project_causes AS causes')
            ->join('project_initiatives AS initiatives', 'initiatives.project_cause_id', '=', 'causes.id')
            ->join('countries', 'initiatives.country_id', '=', 'countries.id')
            ->groupBy('causes.id')
            ->select('causes.name', 'causes.short_desc', 'causes.thumb_src', 'causes.created_at', 'causes.updated_at', 'causes.id')
            ->addSelect(
                DB::raw('GROUP_CONCAT(DISTINCT countries.abbr) AS countries')
            )
            ->get());
    }

    private function initiatives($cause_id)
    {
        return collect($this->connection()
            ->table('project_initiative_types')
            ->join(
                'project_initiatives AS initiatives', 'initiatives.id', '=', 'project_initiative_types.project_initiative_id'
            )
            ->join('countries', 'initiatives.country_id', '=', 'countries.id')
            ->join(
                'project_types AS types', 'types.id', '=', 'project_initiative_types.project_type_id'
            )
            ->leftJoin('project_launch_dates', 'project_launch_dates.project_initiative_id', '=', 'initiatives.id')
            ->groupBy('project_initiative_types.id')
            ->where('initiatives.project_cause_id', $cause_id)
            ->select(
                'types.name', 'types.short_desc', 'types.thumb_src',
                'initiatives.created_at', 'initiatives.updated_at', 'initiatives.started_at',
                'initiatives.ended_at', 'countries.abbr AS country_code', 'project_initiative_types.id'
            )
            ->addSelect(
                DB::raw('GROUP_CONCAT(DISTINCT project_launch_dates.launch_date) AS launch_dates')
            )
            ->get());
    }

    private function projects()
    {
        return collect($this->connection()
            ->table('sponsored_projects')
            ->join('project_initiatives', 'sponsored_projects.project_initiative_id', '=', 'project_initiatives.id')
            ->join('project_types', 'sponsored_projects.project_type_id', '=', 'project_types.id')
            ->join('project_initiative_types', function($join) {
                $join->on('project_initiative_types.project_initiative_id', '=', 'project_initiatives.id')
                     ->on('project_initiative_types.project_type_id', '=', 'project_types.id');
            })
            ->join('project_launch_dates', 'sponsored_projects.project_launch_date_id', '=', 'project_launch_dates.id')
            ->join('plaque_msg_prefixes', 'sponsored_projects.plaque_msg_prefix_id', '=', 'plaque_msg_prefixes.id')
            ->leftJoin('fundraisers', function($join) {
                $join->on('fundraisers.fundable_id', '=', 'sponsored_projects.id')
                     ->where('fundraisers.fundable_type', '=', 'SponsoredProject');
            })
            ->leftJoin('slugs', function($join) {
                $join->on('slugs.slugable_id', '=', 'sponsored_projects.id')
                     ->where('slugs.slugable_type', '=', 'Fundraiser');
            })
            ->leftJoin('uploads', function($join) {
                $join->on('fundraisers.id', '=', 'uploads.uploadable_id')
                     ->where('uploads.uploadable_type', '=', 'Fundraiser');
            })
            ->groupBy('sponsored_projects.id')
            ->select(
                'sponsored_projects.name', 'project_initiative_types.id AS initiative_id', 'sponsored_projects.id',
                'sponsored_projects.plaque_msg', 'sponsored_projects.created_at', 'sponsored_projects.updated_at',
                'project_initiative_types.amount', 'project_launch_dates.launch_date', 'project_launch_dates.half_due_days',
                'project_launch_dates.full_due_days', 'plaque_msg_prefixes.prefix', 'sponsored_projects.completed',
                'sponsored_projects.sponsorable_id', 'sponsored_projects.sponsorable_type', 'sponsored_projects.deleted_at',
                'fundraisers.name AS fundraiser_name', 'fundraisers.deadline', 'fundraisers.video_id', 'fundraisers.video_type',
                'fundraisers.support_msg', 'fundraisers.public', 'fundraisers.created_at AS fundraiser_created',
                'fundraisers.updated_at AS fundraiser_updated', 'fundraisers.deleted_at AS fundraiser_deleted', 'slugs.name AS slug'
            )
            ->addSelect(
                DB::raw('GROUP_CONCAT(DISTINCT uploads.uuid) AS photos')
            )
            ->get());
    }

    private function project_optional_costs($projectId)
    {
        return collect($this->connection()
            ->table('sponsored_project_enhancements AS spe')
            ->join('project_enhancements AS pe', 'spe.project_enhancement_id', '=', 'pe.id')
            ->where('sponsored_project_id', $projectId)
            ->select('pe.name', 'pe.short_desc', 'spe.amount')
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
