<?php
/**
 * User Factory
 */
$factory->define(App\Models\v1\User::class, function (Faker\Generator $faker)
{
    $name = $faker->name;

    return [
        'name'             => $name,
        'email'            => $faker->unique()->safeEmail,
        'alt_email'        => $faker->optional(0.5)->safeEmail,
        'password'         => str_random(10),
        'gender'           => $faker->randomElement(['male', 'female']),
        'status'           => $faker->randomElement(['single', 'married']),
        'birthday'         => $faker->dateTimeBetween('-60 years', '-12 years'),
        'phone_one'        => $faker->optional(0.5)->phoneNumber,
        'phone_two'        => $faker->optional(0.5)->phoneNumber,
        'address'          => $faker->optional(0.5)->streetAddress,
        'city'             => $faker->optional(0.5)->city,
        'state'            => $faker->optional(0.5)->state,
        'zip'              => $faker->optional(0.5)->postcode,
        'country_code'     => strtolower($faker->countryCode),
        'timezone'         => $faker->timezone,
        'bio'              => $faker->optional(0.5)->realText(120),
        'url'              => str_slug($name).'-'.time(),
        'public'           => $faker->boolean(50),
        'remember_token'   => str_random(10),
        'avatar_upload_id' => $faker->randomElement(\App\Models\v1\Upload::where('type', 'avatar')->lists('id')->toArray()),
        'banner_upload_id' => $faker->randomElement(\App\Models\v1\Upload::where('type', 'banner')->lists('id')->toArray())
    ];
});

/**
 * Admin User Factory
 */
$factory->defineAs(App\Models\v1\User::class, 'admin', function (Faker\Generator $faker) use ($factory)
{
    $user = $factory->raw(App\Models\v1\User::class);

    return array_merge($user, [
        'name'     => 'Admin',
        'url'      => 'admin',
        'email'    => 'admin@admin.com',
        'password' => 'secret'
    ]);
});

/**
 * Joe User Factory
 */
$factory->defineAs(App\Models\v1\User::class, 'joe', function (Faker\Generator $faker) use ($factory)
{
    $user = $factory->raw(App\Models\v1\User::class);

    return array_merge($user, [
        'name'     => 'Joe',
        'email'    => 'joe@example.com',
        'password' => 'secret'
    ]);
});

/**
 * Reservation Factory
 */
$factory->define(App\Models\v1\Reservation::class, function (Faker\Generator $faker)
{
    return [
        'given_names'        => $faker->firstName . ' ' . $faker->firstName,
        'surname'            => $faker->lastName,
        'gender'             => $faker->randomElement(['male', 'female']),
        'status'             => $faker->randomElement(['single', 'married']),
        'birthday'           => $faker->dateTimeBetween('-60 years', '-12 years'),
        'shirt_size'         => $faker->randomElement(array_keys(App\Utilities\v1\ShirtSize::all())),
        'user_id'            => $faker->randomElement(App\Models\v1\User::lists('id')->toArray()),
        'address'            => $faker->address,
        'city'               => $faker->city,
        'zip'                => $faker->postcode,
        'country_code'       => $faker->countryCode,
        'email'              => $faker->safeEmail,
        'phone_one'          => $faker->phoneNumber,
        'phone_two'          => $faker->phoneNumber,
        'trip_id'            => $faker->randomElement(App\Models\v1\Trip::lists('id')->toArray()),
        'companion_limit'    => random_int(0, 3),
        'avatar_upload_id'   => $faker->randomElement(\App\Models\v1\Upload::where('type', 'avatar')->lists('id')->toArray())
    ];
});

/**
 * Assignment Factory
 */
$factory->define(App\Models\v1\Assignment::class, function (Faker\Generator $faker)
{
    return [
        'given_names' => $faker->firstName . ' ' . $faker->firstName,
        'surname'     => $faker->lastName,
        'gender'      => $faker->randomElement(['male', 'female']),
        'status'      => $faker->randomElement(['single', 'married']),
        'birthday'    => $faker->dateTimeBetween('-60 years', '-12 years'),
        'user_id'     => $faker->randomElement(App\Models\v1\User::lists('id')->toArray()),
        'campaign_id' => $faker->randomElement(App\Models\v1\Campaign::lists('id')->toArray()),
        'type'        => $faker->randomElement(['translator', 'coordinator', 'transportation'])
    ];
});

/**
 * Trip Factory
 */
$factory->define(App\Models\v1\Trip::class, function (Faker\Generator $faker)
{
    $campaign = new App\Models\v1\Campaign;

    $campaign = $campaign->find($campaign->lists('id')->random());

    return [
        'group_id'        => $faker->randomElement(App\Models\v1\Group::pluck('id')->toArray()),
        'campaign_id'     => $campaign->id,
        'spots'           => random_int(0, 500),
        'companion_limit' => random_int(0, 3),
        'country_code'    => $campaign->country_code,
        'type'            => $faker->randomElement(['ministry', 'family', 'international', 'leader', 'medical', 'media']),
        'difficulty'      => $faker->randomElement(['level_1', 'level_2', 'level_3']),
        'started_at'      => $campaign->started_at,
        'ended_at'        => $campaign->ended_at,
        'todos'           => ['Send shirt', 'Send wrist band', 'Enter into LGL', 'Send launch guide', 'Send luggage tag'],
        'prospects'       => $faker->randomElements([
            'adults', 'teens', 'men', 'women', 'medical professionals',
            'media professionals', 'business professionals', 'pastors',
            'families'], 4),
        'rep_id'          => $faker->randomElement(App\Models\v1\User::pluck('id')->toArray()),
        'description'      => file_get_contents(resource_path('assets/sample_trip.md')),
        'published_at'     => $faker->optional(0.9)->dateTimeInInterval($campaign->published_at, '+ 1 month'),
        'closed_at'        => $faker->dateTimeInInterval($campaign->started_at, '- 7 days')
    ];
});

/**
 * Campaign Factory
 */
$factory->defineAs(App\Models\v1\Campaign::class, '1n1d2017', function (Faker\Generator $faker)
{
    return [
        'name'             => 'One Nation One Day 2017',
        'country_code'     => 'ni',
        'short_desc'       => '1Nation1Day Nicaragua will be the largest global missions outreach in history. But this isn’t just about numbers; it\'s about creating measurable change. It takes an unprecedented strategy to make this audacious vision a reality.',
        'page_url'         => '1n1d17',
        'page_src'         => '_1n1d2017',
        'started_at'       => '2017-07-22 00:00:00',
        'ended_at'         => '2017-07-30 22:59:59',
        'published_at'     => '2016-01-01 00:00:00',
        'avatar_upload_id' => $faker->randomElement(\App\Models\v1\Upload::where('type', 'avatar')->lists('id')->toArray()),
        'banner_upload_id' => $faker->randomElement(\App\Models\v1\Upload::where('type', 'banner')->lists('id')->toArray())
    ];
});

$factory->defineAs(App\Models\v1\Campaign::class, 'india', function (Faker\Generator $faker)
{
    return [
        'name'             => 'Christmas in India 2016',
        'country_code'     => 'in',
        'short_desc'       => 'Venture deep into southern India as together we Rescue EVERY Child in several villages in the state of Andhra Pradesh. Watch as they enjoy their first Christmas and shower them with more Christmas gifts than their little arms can hold.',
        'page_url'         => 'christmasinindia2016',
        'page_src'         => '_india',
        'started_at'       => '2016-12-03 00:00:00',
        'ended_at'         => '2016-12-11 22:59:59',
        'published_at'     => '2016-01-01 00:00:00',
        'avatar_upload_id' => $faker->randomElement(\App\Models\v1\Upload::where('type', 'avatar')->lists('id')->toArray()),
        'banner_upload_id' => $faker->randomElement(\App\Models\v1\Upload::where('type', 'banner')->lists('id')->toArray())
    ];
});


/**
 * Group Factory
 */
$factory->define(App\Models\v1\Group::class, function (Faker\Generator $faker)
{
    $company = $faker->company;

    return [
        'name'             => $company,
        'type'             => $faker->randomElement(['church', 'business', 'youth', 'nonprofit', 'other']),
        'description'      => $faker->realText(120),
        'timezone'         => $faker->timezone,
        'address_one'      => $faker->optional(0.5)->streetAddress,
        'address_two'      => $faker->optional(0.5)->buildingNumber,
        'city'             => $faker->optional(0.5)->city,
        'state'            => $faker->optional(0.5)->state,
        'zip'              => $faker->optional(0.5)->postcode,
        'country_code'     => strtolower($faker->countryCode),
        'phone_one'        => $faker->optional(0.5)->phoneNumber,
        'phone_two'        => $faker->optional(0.5)->phoneNumber,
        'email'            => $faker->safeEmail,
        'public'           => $faker->boolean(75),
        'url'              => str_slug($company),
        'avatar_upload_id' => $faker->randomElement(\App\Models\v1\Upload::where('type', 'avatar')->lists('id')->toArray()),
        'banner_upload_id' => $faker->randomElement(\App\Models\v1\Upload::where('type', 'banner')->lists('id')->toArray())
    ];
});

/**
 * Manager Factory
 */
$factory->define(App\Models\v1\Manager::class, function (Faker\Generator $faker)
{
    return [
        'group_id' => $faker->randomElement(App\Models\v1\Group::lists('id')->toArray()),
        'user_id'  => $faker->randomElement(App\Models\v1\User::lists('id')->toArray())
    ];
});

/**
 * Facilitator Factory
 */
$factory->define(App\Models\v1\Facilitator::class, function (Faker\Generator $faker)
{
    return [
        'trip_id' => $faker->randomElement(App\Models\v1\Trip::lists('id')->toArray()),
        'user_id' => $faker->randomElement(App\Models\v1\User::lists('id')->toArray())
    ];
});

/**
 * Deadline Factory
 */
$factory->define(App\Models\v1\Deadline::class, function (Faker\Generator $faker)
{
    return [
        'name'                     => $faker->bs,
        'date'                     => $faker->dateTimeThisYear('+ 6 months'),
        'grace_period'             => random_int(0, 10),
        'enforced'                 => $faker->boolean(50),
        'deadline_assignable_type' => 'trips',
        'deadline_assignable_id'   => $faker->randomElement(App\Models\v1\Trip::lists('id')->toArray()),
    ];
});

/**
 * Note Factory
 */
$factory->define(App\Models\v1\Note::class, function (Faker\Generator $faker)
{
    return [
        'subject'       => $faker->catchPhrase,
        'content'       => $faker->realText(120),
        'user_id'       => $faker->randomElement(App\Models\v1\User::lists('id')->toArray()),
        'noteable_type' => $faker->randomElement([
            'reservations', 'trips', 'users', 'donations', 'fundraisers', 'projects', 'groups'
        ]),
        'noteable_id'   => $faker->randomElement(App\Models\v1\Reservation::lists('id')->toArray()),
    ];
});

/**
 * Companion Factory
 */
$factory->define(App\Models\v1\Companion::class, function (Faker\Generator $faker)
{
    return [
        'reservation_id'           => $faker->randomElement(App\Models\v1\Reservation::lists('id')->toArray()),
        'companion_reservation_id' => $faker->randomElement(App\Models\v1\Reservation::lists('id')->toArray()),
        'relationship'             => $faker->randomElement([
            'friend', 'family', 'spouse', 'dependent', 'guardian'
        ])
    ];
});

/**
 * Cost Factory
 */
$factory->define(App\Models\v1\Cost::class, function (Faker\Generator $faker)
{
    return [
        'name'                 => $faker->catchPhrase,
        'description'          => $faker->sentence(10),
        'active_at'            => $faker->dateTimeThisYear('+ 6 months'),
        'amount'               => $faker->numberBetween($min = 1000, $max = 3000),
        'type'                 => $faker->randomElement(['incremental', 'static', 'optional']),
        'cost_assignable_type' => 'trips',
        'cost_assignable_id'   => $faker->randomElement(App\Models\v1\Trip::lists('id')->toArray()),
    ];
});

// Super Early
$factory->defineAs(App\Models\v1\Cost::class, 'super', function (Faker\Generator $faker)
{
    return [
        'name'                 => 'Super Early Registration',
        'active_at'            => '2016-01-01 00:00:00',
        'amount'               => $faker->numberBetween($min = 1000, $max = 1200),
        'type'                 => 'incremental',
    ];
});

// Early
$factory->defineAs(App\Models\v1\Cost::class, 'early', function (Faker\Generator $faker)
{
    return [
        'name'                 => 'Early Registration',
        'active_at'            => '2017-01-01 00:00:00',
        'amount'               => $faker->numberBetween($min = 1300, $max = 1500),
        'type'                 => 'incremental',
    ];
});

// General Registration
$factory->defineAs(App\Models\v1\Cost::class, 'general', function (Faker\Generator $faker)
{
    return [
        'name'                 => 'General Registration',
        'active_at'            => '2017-03-01 00:00:00',
        'amount'               => $faker->numberBetween($min = 1600, $max = 1800),
        'type'                 => 'incremental',
    ];
});

// Late Registration
$factory->defineAs(App\Models\v1\Cost::class, 'late', function (Faker\Generator $faker)
{
    return [
        'name'                 => 'Late Registration',
        'active_at'            => '2017-07-01 00:00:00',
        'amount'               => $faker->numberBetween($min = 1900, $max = 2000),
        'type'                 => 'incremental',
    ];
});

// Double Room Request
$factory->defineAs(App\Models\v1\Cost::class, 'double', function (Faker\Generator $faker)
{
    return [
        'name'                 => 'Double Room Request',
        'active_at'            => '2016-01-01 00:00:00',
        'amount'               => 225,
        'type'                 => 'optional',
    ];
});

// Triple Room Request
$factory->defineAs(App\Models\v1\Cost::class, 'triple', function (Faker\Generator $faker)
{
    return [
        'name'                 => 'Triple Room Request',
        'active_at'            => '2016-01-01 00:00:00',
        'amount'               => 150,
        'type'                 => 'optional',
    ];
});

// Deposit
$factory->defineAs(App\Models\v1\Cost::class, 'deposit', function (Faker\Generator $faker)
{
    return [
        'name'                 => 'Deposit',
        'active_at'            => '2016-01-01 00:00:00',
        'amount'               => 100,
        'type'                 => 'static',
    ];
});

// Static
$factory->defineAs(App\Models\v1\Cost::class, 'static', function (Faker\Generator $faker)
{
    return [
        'name'                 => $faker->catchPhrase,
        'active_at'            => '2016-01-01 00:00:00',
        'amount'               => random_int(25000, 100000),
        'type'                 => 'static',
    ];
});

/**
 * Payment Factory
 */
$factory->define(App\Models\v1\Payment::class, function (Faker\Generator $faker)
{
    $due_first = $faker->dateTimeThisYear('+ 6 months');
    $due_second = \Carbon\Carbon::parse(date_format($due_first, 'Y-M-d h:i:s'))->addMonths(6)->toDateTimeString();

    return [
        'cost_id'      => $faker->randomElement(App\Models\v1\Cost::lists('id')->toArray()),
        'percent_owed' => random_int(10, 100),
        'amount_owed'  => $faker->randomNumber(4) / 2,
        'due_at'       => $faker->randomElement([$due_first, $due_second]),
        'grace_period' => random_int(1, 3),
        'upfront'      => $faker->boolean(50)
    ];
});

/**
 * Requirement Factory
 */
$factory->define(App\Models\v1\Requirement::class, function (Faker\Generator $faker)
{
    return [
        'name'            => $faker->randomElement(['Passport', 'Medical Release', 'Visa', 'Referral', 'Testimony']),
        'document_type'   => $faker->randomElement(['passports', 'medical_releases', 'visas', 'referrals', 'essays']),
        'short_desc'      => $faker->realText(120),
        'due_at'          => $faker->dateTimeThisYear('+ 6 months'),
        'grace_period'    => random_int(0, 10),
        'requester_type' => 'trips',
        'requester_id'   => $faker->randomElement(App\Models\v1\Trip::lists('id')->toArray()),
    ];
});

/**
 * Fundraiser Factory
 */
$factory->define(App\Models\v1\Fundraiser::class, function (Faker\Generator $faker)
{
    $start_date = \Carbon\Carbon::now();
    $end_date = \Carbon\Carbon::parse($start_date)->addYear;

    return [
        'name'             => $faker->catchPhrase,
        'type'             => $faker->randomElement(['general', 'envelope']),
        'goal_amount'      => $faker->numberBetween(1000, 3000),
        'description'      => file_get_contents(resource_path('assets/sample_fundraiser.md')),
        'url'              => $faker->unique()->slug(3),
        'started_at'       => $start_date,
        'ended_at'         => $end_date,
        'public'           => $faker->boolean(50)
    ];
});

/**
 * Donation Factory
 */
$factory->define(App\Models\v1\Donation::class, function (Faker\Generator $faker)
{
    return [
        'amount'               => $faker->numberBetween(1, 1000),
        'currency'             => $faker->currencyCode,
        'description'          => $faker->realText(120),
        'message'              => $faker->realText(120),
        'anonymous'            => $faker->boolean(25),
        'designation_id'       => $faker->randomElement(App\Models\v1\Reservation::pluck('id')->toArray()),
        'designation_type'     => 'reservations',
        'donor_id'             => $faker->randomElement(App\Models\v1\Donor::pluck('id')->toArray()),
        'payment_type'         => 'card',
    ];
});

/**
 * Donor Factory
 */
$factory->define(App\Models\v1\Donor::class, function (Faker\Generator $faker)
{
    return [
        'name'         => $faker->name,
        'email'        => $faker->safeEmail,
        'phone'        => $faker->phoneNumber,
        'company'      => $faker->optional(0.7)->company,
        'zip'          => $faker->postcode,
        'country_code' => $faker->countryCode,
        'address'      => $faker->streetAddress,
        'city'         => $faker->city,
        'state'        => $faker->state,
    ];
});

/**
 * Passport Factory
 */
$factory->define(App\Models\v1\Passport::class, function (Faker\Generator $faker)
{
    return [
        'given_names'   => $faker->firstName,
        'surname'       => $faker->lastName,
        'number'        => $faker->randomNumber(9),
        'issued_at'     => $faker->dateTimeThisYear,
        'expires_at'    => $faker->dateTimeThisDecade,
        'birth_country' => $faker->randomElement(['us', 'ca', 'gb', 'hn']),
        'citizenship'   => $faker->randomElement(['us', 'ca', 'gb', 'hn']),
        'user_id'       => $faker->randomElement(App\Models\v1\User::pluck('id')->toArray()),
        'upload_id' => $faker->randomElement(\App\Models\v1\Upload::pluck('id')->toArray())
    ];
});

/**
 * Visa Factory
 */
$factory->define(App\Models\v1\Visa::class, function (Faker\Generator $faker)
{
    return [
        'given_names'  => $faker->firstName,
        'surname'      => $faker->lastName,
        'number'       => $faker->randomNumber(9),
        'issued_at'    => $faker->dateTimeThisYear,
        'expires_at'   => $faker->dateTimeThisDecade,
        'country_code' => strtolower($faker->countryCode),
        'user_id'      => $faker->randomElement(App\Models\v1\User::pluck('id')->toArray()),
        'upload_id' => $faker->randomElement(\App\Models\v1\Upload::pluck('id')->toArray())
    ];
});

/**
 * Contact Factory
 */
$factory->define(App\Models\v1\Contact::class, function (Faker\Generator $faker)
{
    return [
        'user_id'        => $faker->randomElement(App\Models\v1\User::pluck('id')->toArray()),
        'suffix'         => $faker->randomElement(['mr', 'miss', 'mrs', 'dr', 'ps', 'sir']),
        'first_name'     => $faker->firstName,
        'last_name'      => $faker->optional(0.5)->lastName,
        'email'          => $faker->optional(0.5)->safeEmail,
        'phone_one'      => $faker->optional(0.5)->phoneNumber,
        'phone_two'      => $faker->optional(0.5)->phoneNumber,
        'address_street' => $faker->optional(0.5)->streetAddress,
        'address_city'   => $faker->optional(0.5)->city,
        'address_state'  => $faker->optional(0.5)->state,
        'address_zip'    => $faker->optional(0.5)->postcode,
        'country_code'   => strtolower($faker->optional(0.5)->countryCode),
        'emergency'      => $faker->boolean(10),
        'relationship'   => $faker->randomElement(['guardian', 'family', 'spouse', 'friend', 'other'])
    ];
});

/**
 * Medical Release Factory
 */
$factory->define(App\Models\v1\MedicalRelease::class, function (Faker\Generator $faker)
{
    return [
        'user_id'       => $faker->randomElement(App\Models\v1\User::pluck('id')->toArray()),
        'name'          => $faker->firstName . ' ' . $faker->lastName,
        'ins_provider'  => $faker->company,
        'ins_policy_no' => $faker->ean8,
        'is_risk'       => $faker->boolean(50)
    ];
});

/**
 * Medical Condition Factory
 */
$factory->define(App\Models\v1\MedicalCondition::class, function (Faker\Generator $faker)
{
    return [
        'name' => $faker->randomElement(App\Models\v1\MedicalCondition::available()),
        'medication' => $faker->boolean(),
        'diagnosed' => $faker->boolean()
    ];
});

/**
 * Medical Allergy Factory
 */
$factory->define(App\Models\v1\MedicalAllergy::class, function (Faker\Generator $faker)
{
    return [
        'name' => $faker->randomElement(App\Models\v1\MedicalAllergy::available()),
        'medication' => $faker->boolean(),
        'diagnosed' => $faker->boolean()
    ];
});

/**
 * Referral Factory
 */
$factory->define(App\Models\v1\Referral::class, function (Faker\Generator $faker)
{
    return [
        'name' => $faker->firstName,
        'type' => 'pastoral',
        'referral_name' => $faker->name,
        'referral_phone' => $faker->phoneNumber,
        'referral_email' => $faker->email,
        'status' => 'sent',
        'response' => [
            [
                'q' => 'How Long have you known the applicant?',
                'a' => ''
            ],
            [
                'q' => 'Please list any current roles the applicant serves in at your church:',
                'a' => ''
            ],
            [
                'q' => 'To the best of your knowledge, what is the current state of the applicant\'s spiritual walk?',
                'a' => ''
            ],
            [
                'q' => 'Do you have any reservations about sending this applicant into a foreign nation where spiritual, physical, and social endurance is tested?',
                'a' => ''
            ],
            [
                'q' => 'What are the applicant\'s significant strengths?',
                'a' => ''
            ],
            [
                'q' => 'What are the applicant\'s significant weaknesses?',
                'a' => ''
            ],
            [
                'q' => 'Would you recommend this applicant for a leadership role with Missions.me? If so, why?',
                'a' => ''
            ]
        ]
    ];
});

/**
 * Interaction/Decision Factory
 */
$factory->define(App\Models\v1\Interaction\Decision::class, function (Faker\Generator $faker)
{
    return [
        'team_member_id' => $faker->randomElement(App\Models\v1\TeamMember::pluck('id')->toArray()),
        'region_id'      => $faker->randomElement(App\Models\v1\Region::pluck('id')->toArray()),
        'name'           => $faker->name,
        'gender'         => $faker->randomElement(['male', 'female']),
        'age_group'      => $faker->randomElement(['child', 'youth', 'adult']),
        'phone'          => $faker->phoneNumber,
        'email'          => $faker->safeEmail,
        'lat'            => $faker->latitude,
        'long'           => $faker->longitude,
        'decision'       => $faker->boolean(50)
    ];
});


/**
 * Interaction/Exam Factory
 */
$factory->define(App\Models\v1\Interaction\Exam::class, function (Faker\Generator $faker)
{
    return [
        'team_member_id' => $faker->randomElement(App\Models\v1\TeamMember::pluck('id')->toArray()),
        'region_id'      => $faker->randomElement(App\Models\v1\Region::pluck('id')->toArray()),
        'name'           => $faker->name,
        'gender'         => $faker->randomElement(['male', 'female']),
        'age_group'      => $faker->randomElement(['child', 'youth', 'adult']),
        'phone'          => $faker->phoneNumber,
        'email'          => $faker->safeEmail,
        'lat'            => $faker->latitude,
        'long'           => $faker->longitude,
        'party_size'     => random_int(1, 4),
        'decision'       => $faker->boolean(50),
        'treatments'     => $faker->randomElements([
            'medical', 'medication', 'dental', 'eye glasses', 'other'
        ], 3)
    ];
});

/**
 * Interaction/Site Factory
 */
$factory->define(App\Models\v1\Interaction\Site::class, function (Faker\Generator $faker)
{
    return [
        'team_member_id'  => $faker->randomElement(App\Models\v1\TeamMember::pluck('id')->toArray()),
        'region_id'       => $faker->randomElement(App\Models\v1\Region::pluck('id')->toArray()),
        'call_sign'       => $faker->randomNumber(1),
        'site_type'       => $faker->randomElement([
            'plaza', 'school', 'park', 'market', 'neighborhood', 'stadium', 'church', 'other'
        ]),
        'total_reached'   => random_int(1, 500),
        'total_decisions' => random_int(1, 300),
        'lat'             => $faker->latitude,
        'long'            => $faker->longitude
    ];
});

/**
 * Region Factory
 */
$factory->define(App\Models\v1\Region::class, function (Faker\Generator $faker)
{
    return [
        'name'         => $faker->city,
        'country_code' => strtolower($faker->countryCode),
        'call_sign'    => $faker->safeColorName,
        'campaign_id'  => $faker->randomElement(App\Models\v1\Campaign::pluck('id')->toArray()),
    ];
});

/**
 * Team Factory
 */
$factory->define(App\Models\v1\Team::class, function (Faker\Generator $faker)
{
    return [
        'call_sign'    => $faker->randomNumber(2),
        'region_id'    => $faker->randomElement(App\Models\v1\Region::pluck('id')->toArray()),
        'published_at' => $faker->dateTimeThisYear
    ];
});

/**
 * Team Member Factory "Reservation"
 */
$factory->define(App\Models\v1\TeamMember::class, function (Faker\Generator $faker)
{
    return [
        'team_id'         => $faker->randomElement(App\Models\v1\Team::pluck('id')->toArray()),
        'assignable_id'   => $faker->randomElement(App\Models\v1\Reservation::pluck('id')->toArray()),
        'assignable_type' => 'reservations',
        'role_id'         => $faker->randomNumber(1),
        'group'           => $faker->randomNumber(1),
        'leader'          => $faker->boolean(25),
        'forms'           => $faker->randomElements(['decision', 'exam', 'site'], 2)
    ];
});

/**
 * Team Member Factory "Assignment"
 */
$factory->defineAs(App\Models\v1\TeamMember::class, 'assignment', function (Faker\Generator $faker) use ($factory)
{
    $member = $factory->raw(App\Models\v1\TeamMember::class);

    return array_merge($member, [
        'assignable_id'   => $faker->randomElement(App\Models\v1\Assignment::pluck('id')->toArray()),
        'assignable_type' => 'assignments',
        'forms'           => ['decision']
    ]);
});

/**
 * Transport Factory
 */
$factory->define(App\Models\v1\Transport::class, function (Faker\Generator $faker)
{
    return [
        'campaign_id' => $faker->randomElement(App\Models\v1\Campaign::pluck('id')->toArray()),
        'type'        => $faker->randomElement(['flight', 'bus', 'other']),
        'vessel_no'   => $faker->numerify('DL####'),
        'name'        => $faker->company,
        'call_sign'   => $faker->optional(0.5)->safeColorName,
        'domestic'    => $faker->boolean(25),
        'capacity'    => $faker->randomNumber(2),
        'departs_at'  => $faker->dateTimeThisYear,
        'arrives_at'  => $faker->dateTimeThisYear
    ];
});

/**
 * Passenger Factory
 */
$factory->define(App\Models\v1\Passenger::class, function (Faker\Generator $faker)
{
    return [
        'transport_id'   => $faker->randomElement(App\Models\v1\Transport::pluck('id')->toArray()),
        'reservation_id' => $faker->randomElement(App\Models\v1\Reservation::pluck('id')->toArray()),
        'seat_no'        => $faker->randomNumber(2) . '' . $faker->randomLetter
    ];
});

/**
 * Accommodation Factory
 */
$factory->define(App\Models\v1\Accommodation::class, function (Faker\Generator $faker)
{
    return [
        'name'         => $faker->company,
        'address_one'  => $faker->streetAddress,
        'city'         => $faker->city,
        'state'        => $faker->state,
        'zip'          => $faker->postcode,
        'phone'        => $faker->phoneNumber,
        'fax'          => $faker->optional(0.5)->phoneNumber,
        'country_code' => strtolower($faker->countryCode),
        'email'        => $faker->optional(0.5)->companyEmail,
        'url'          => $faker->optional(0.5)->url,
        'region_id'    => $faker->randomElement(App\Models\v1\Region::pluck('id')->toArray()),
        'short_desc'   => $faker->optional(0.5)->realText(120),
        'check_in_at'  => $faker->dateTimeThisYear,
        'check_out_at' => $faker->dateTimeThisYear
    ];
});

/**
 * Occupant Factory
 */
$factory->define(App\Models\v1\Occupant::class, function (Faker\Generator $faker)
{
    return [
        'accommodation_id' => $faker->randomElement(App\Models\v1\Accommodation::pluck('id')->toArray()),
        'reservation_id'   => $faker->randomElement(App\Models\v1\Reservation::pluck('id')->toArray()),
        'room_no'          => $faker->numerify('Rm ##') . ' - ' . $faker->randomElement(['single', 'double']),
        'room_leader'      => $faker->boolean(25)
    ];
});

/**
 * Upload Factory
 */
$factory->defineAs(App\Models\v1\Upload::class, 'avatar', function (Faker\Generator $faker)
{
    return [
        'name' => $faker->word,
        'source' => $faker->randomElement([
            'images/avatars/avatar1.jpg',
            'images/avatars/avatar2.jpg',
            'images/avatars/avatar3.jpg',
            'images/avatars/avatar4.jpg'
        ]),
        'type' => 'avatar'
    ];
});

$factory->defineAs(App\Models\v1\Upload::class, 'banner', function (Faker\Generator $faker)
{
    return [
        'name' => $faker->word,
        'source' => $faker->randomElement([
            'images/banners/banner1.jpg',
            'images/banners/banner2.jpg',
            'images/banners/banner3.jpg',
            'images/banners/banner4.jpg'
        ]),
        'type' => 'banner'
    ];
});

$factory->define(App\Models\v1\Link::class, function(Faker\Generator $faker)
{
    return [
        'name' => $faker->word,
        'url' => $faker->url
    ];
});

$factory->define(App\Models\v1\Story::class, function(Faker\Generator $faker)
{
    return [
        'title' => $faker->sentence,
        'content' => file_get_contents(resource_path('assets/sample_story.md')),
        'author_id' => $faker->randomElement(App\Models\v1\User::pluck('id')->toArray()),
        'author_type' => 'users'
    ];
});

$factory->define(App\Models\v1\Accolade::class, function(Faker\Generator $faker)
{
    $countries = [];

    for ($i = 0; $i <= 10; $i++) {
        array_push($countries, $faker->countryCode);
    }

    return [
        'display_name' => 'Countries Visited',
        'name'         => 'countries_visited',
        'items'        => $faker->randomElements($countries, 4)
    ];
});

$factory->defineAs(App\Models\v1\Accolade::class, 'trip_history', function(Faker\Generator $faker)
{
    $trips = config('accolades.trips');

    return [
        'display_name' => 'Trip History',
        'name'         => 'trip_history',
        'items'        => $faker->randomElements($trips, 4)
    ];
});

$factory->define(App\Models\v1\Fund::class, function(Faker\Generator $faker)
{
    return [
        'name' => $faker->sentence(4),
        'balance' => $faker->randomNumber,
        'fundable_id' => $faker->randomElement(App\Models\v1\Reservation::pluck('id')->toArray()),
        'fundable_type' => 'reservations',
        'class' => $faker->randomElement(App\Models\v1\Campaign::pluck('Name')->toArray()) . ' - Team',
        'item' => 'Missionary Donation'
    ];
});

/**
 * Transaction Factory
 */
$factory->defineAs(App\Models\v1\Transaction::class, 'donation', function(Faker\Generator $faker)
{
    $fund = $faker->randomElement(App\Models\v1\Fund::get()->toArray());
    $donor = $faker->randomElement(App\Models\v1\Donor::get()->toArray());

//    $date = $faker->randomElement(App\Models\v1\Fundraiser::pluck('started_at')->toArray());
    $date = $faker->dateTimeThisMonth;

    return [
        'fund_id' => $fund['id'],
        'donor_id' => $donor['id'],
        'type' => 'donation',
        'amount' => $faker->randomNumber(2),
        'details' => [
            'type' => 'card',
            'last_four' => substr($faker->creditCardNumber, -4),
            'cardholder' => $faker->name,
            'brand' => $faker->creditCardType,
            'comment' => $faker->realText($maxNbChars = 120, $indexSize = 2)
        ],
        'created_at' => $date,
    ];
});

$factory->defineAs(App\Models\v1\Transaction::class, 'check', function(Faker\Generator $faker)
{
    $fund = $faker->randomElement(App\Models\v1\Fund::get()->toArray());
    $donor = $faker->randomElement(App\Models\v1\Donor::get()->toArray());

//    $date = $faker->randomElement(App\Models\v1\Fundraiser::pluck('started_at')->toArray());
    $date = $faker->dateTimeThisMonth;

    return [
        'fund_id' => $fund['id'],
        'donor_id' => $donor['id'],
        'type' => 'donation',
        'amount' => $faker->randomNumber(2),
        'details' => [
            'type' => 'check',
            'number' => $faker->randomDigitNotNull,
            'comment' => $faker->realText($maxNbChars = 120, $indexSize = 2)
        ],
        'created_at' => $date,
    ];
});

$factory->defineAs(App\Models\v1\Transaction::class, 'anonymous', function(Faker\Generator $faker)
{
    $fund = $faker->randomElement(App\Models\v1\Fund::all()->toArray());

    return [
        'fund_id' => $fund['id'],
        'donor_id' => App\Models\v1\Donor::where('name', 'anonymous')->pluck('id'),
        'type' => 'donation',
        'amount' => $faker->randomNumber(2),
        'details' => [
            'type' => 'card',
            'last_four' => substr($faker->creditCardNumber, -4),
            'cardholder' => $faker->name,
            'brand' => $faker->creditCardType,
        ],
        'created_at' => $faker->dateTimeThisMonth
    ];
});

$factory->defineAs(App\Models\v1\Transaction::class, 'transfer_from', function(Faker\Generator $faker)
{
    $fund = $faker->randomElement(App\Models\v1\Fund::all()->toArray());

    return [
        'fund_id' => $fund['id'],
        'donor_id' => null,
        'type' => 'transfer',
        'amount' => -$faker->randomNumber(2),
        'details' => null,
        'created_at' => $faker->dateTimeThisMonth
    ];
});

$factory->defineAs(App\Models\v1\Transaction::class, 'transfer_to', function(Faker\Generator $faker)
{
    $fund = $faker->randomElement(App\Models\v1\Fund::all()->toArray());

    return [
        'fund_id' => $fund['id'],
        'donor_id' => null,
        'type' => 'transfer',
        'amount' => $faker->randomNumber(2),
        'details' => null,
        'created_at' => $faker->dateTimeThisMonth
    ];
});

/**
 * Trip Interest Factory
 */
$factory->define(App\Models\v1\TripInterest::class, function(Faker\Generator $faker) {
    return [
        'trip_id' => $faker->randomElement(App\Models\v1\Trip::pluck('id')->toArray()),
        'status' => 'undecided',
        'name' => $faker->firstName. ' ' .$faker->lastName,
        'email' => $faker->safeEmail,
        'phone' => $faker->optional(0.5)->phoneNumber,
        'communication_preferences' => $faker->randomElements(['email', 'phone', 'text'], 2)
    ];
});

/**
 * Project Cause Factory
 */
$factory->define(App\Models\v1\ProjectCause::class, function(Faker\Generator $faker) {
    return [
        'name' => $faker->unique()->randomElement(['Angel House', 'Clean Water']),
        'short_desc' => $faker->realText(200),
        'upload_id' => $faker->randomElement(App\Models\v1\Upload::pluck('id')->toArray()),
        'countries' => [$faker->countryCode]
    ];
});

/**
 * Project Initiative Factory
 */
$factory->define(App\Models\v1\ProjectInitiative::class, function(Faker\Generator $faker) {
    return [
        'type' => $faker->word,
        'short_desc' => $faker->realText(200),
        'country_code' => strtolower($faker->countryCode),
        'upload_id' => $faker->randomElement(App\Models\v1\Upload::pluck('id')->toArray()),
        'started_at' => $faker->dateTime,
        'ended_at' => $faker->dateTime
    ];
});

/**
 * Project Factory
 */
$factory->define(App\Models\v1\Project::class, function(Faker\Generator $faker) {
    return [
        'name' => $faker->sentence(3),
        'project_initiative_id' => $faker->randomElement(App\Models\v1\ProjectInitiative::pluck('id')->toArray()),
        'rep_id' => $faker->randomElement(App\Models\v1\User::pluck('id')->toArray()),
        'sponsor_id' => $faker->randomElement(App\Models\v1\User::pluck('id')->toArray()),
        'sponsor_type' => 'users',
        'plaque_prefix' => $faker->randomElement(['in honor of', 'in memory of', 'sponsored by']),
        'plaque_message' => $faker->name
    ];
});

/*
 * Essay Factory
 */
$factory->define(App\Models\v1\Essay::class, function(Faker\Generator $faker) {
    return [
        'author_name' => $faker->firstName . ' ' . $faker->lastName,
        'user_id' => $faker->randomElement(App\Models\v1\User::pluck('id')->toArray()),
        'subject' => 'Testimony',
        'content' => json_decode(file_get_contents(resource_path('assets/sample_testimony.json')))
    ];
});