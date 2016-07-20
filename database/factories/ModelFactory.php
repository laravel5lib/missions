<?php
/**
 * User Factory
 */
$factory->define(App\Models\v1\User::class, function (Faker\Generator $faker)
{
    return [
        'name'           => $faker->name,
        'email'          => $faker->unique()->safeEmail,
        'alt_email'      => $faker->optional(0.5)->safeEmail,
        'password'       => str_random(10),
        'gender'         => $faker->optional(0.5)->randomElement(['male', 'female']),
        'status'         => $faker->optional(0.5)->randomElement(['single', 'married']),
        'birthday'       => $faker->dateTimeBetween('-60 years', '-12 years'),
        'phone_one'      => $faker->optional(0.5)->phoneNumber,
        'phone_two'      => $faker->optional(0.5)->phoneNumber,
        'street'         => $faker->optional(0.5)->streetAddress,
        'city'           => $faker->optional(0.5)->city,
        'state'          => $faker->optional(0.5)->state,
        'zip'            => $faker->optional(0.5)->postcode,
        'country_code'   => $faker->randomElement(['us', 'ca', 'gb', 'au', 'za', 'hn', 'do']),
        'timezone'       => $faker->timezone,
        'bio'            => $faker->optional(0.5)->sentence(6),
        'url'            => $faker->randomElement([$faker->userName, str_random(10)]),
        'public'         => $faker->boolean(50),
        'remember_token' => str_random(10),
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
        'email'    => 'admin@admin.com',
        'password' => 'secret',
        'admin'    => true
    ]);
});

/**
 * Reservation Factory
 */
$factory->define(App\Models\v1\Reservation::class, function (Faker\Generator $faker)
{
    return [
        'given_names'     => $faker->firstName . ' ' . $faker->firstName,
        'surname'         => $faker->lastName,
        'gender'          => $faker->randomElement(['male', 'female']),
        'status'          => $faker->randomElement(['single', 'married']),
        'birthday'        => $faker->dateTimeBetween('-60 years', '-12 years'),
        'shirt_size'      => $faker->randomElement(array_keys(App\Utilities\v1\ShirtSize::all())),
        'user_id'         => $faker->randomElement(App\Models\v1\User::lists('id')->toArray()),
        'trip_id'         => $faker->randomElement(App\Models\v1\Trip::lists('id')->toArray()),
        'companion_limit' => random_int(0, 3),
        'passport_id'     => $faker->randomElement(App\Models\v1\Passport::lists('id')->toArray()),
        'visa_id'         => $faker->randomElement(App\Models\v1\Visa::lists('id')->toArray()),
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
    $started_at = $faker->dateTimeBetween('now', '+ 6 months');
    $ended_at = $faker->dateTimeBetween('now', '+ 2 weeks');

    return [
        'group_id'        => $faker->randomElement(App\Models\v1\Group::lists('id')->toArray()),
        'campaign_id'     => $faker->randomElement(App\Models\v1\Campaign::lists('id')->toArray()),
        'rep_id'          => random_int(1, 99),
        'spots'           => random_int(0, 500),
        'companion_limit' => random_int(0, 3),
        'country_code'    => $faker->randomElement(['hn', 'ni', 'ec']),
        'type'            => $faker->randomElement(['full', 'short', 'medical', 'media']),
        'difficulty'      => $faker->randomElement(['level_1', 'level_2', 'level_3']),
        'started_at'      => $started_at,
        'ended_at'        => $ended_at,
        'todos'           => $faker->sentences(6),
        'prospects'       => $faker->randomElements([
            'adults', 'teens', 'men', 'women', 'medical professionals',
            'media professionals', 'business professionals', 'pastors',
            'families'], 4),
        'description'     => $faker->paragraph(10),
        'published_at'    => $faker->optional(0.9)->dateTimeThisYear,
        'closed_at'       => $faker->dateTimeThisYear
    ];
});

/**
 * Campaign Factory
 */
$factory->define(App\Models\v1\Campaign::class, function (Faker\Generator $faker)
{
    return [
        'name'         => $faker->catchPhrase,
        'country_code' => $faker->randomElement(['hn', 'ni', 'ec']),
        'short_desc'   => $faker->paragraph(3),
        'page_url'     => $faker->word,
        'thumb_src'    => $faker->imageUrl,
        'started_at'   => $faker->dateTimeThisYear,
        'ended_at'     => $faker->dateTimeThisYear,
        'published_at' => $faker->dateTimeThisYear,
    ];
});

/**
 * Group Factory
 */
$factory->define(App\Models\v1\Group::class, function (Faker\Generator $faker)
{
    return [
        'name'         => $faker->company,
        'type'         => $faker->randomElement(['church', 'business', 'youth', 'nonprofit', 'other']),
        'description'  => $faker->paragraph(2),
        'timezone'     => $faker->timezone,
        'address_one'  => $faker->optional(0.5)->streetAddress,
        'address_two'  => $faker->optional(0.5)->buildingNumber,
        'city'         => $faker->optional(0.5)->city,
        'state'        => $faker->optional(0.5)->state,
        'zip'          => $faker->optional(0.5)->postcode,
        'country_code' => $faker->randomElement(['us', 'ca', 'gb', 'au', 'za', 'hn', 'do']),
        'phone_one'    => $faker->optional(0.5)->phoneNumber,
        'phone_two'    => $faker->optional(0.5)->phoneNumber,
        'email'        => $faker->optional(0.5)->companyEmail,
        'public'       => $faker->boolean(50),
        'url'          => $faker->userName
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
 * Todo Factory
 */
$factory->define(App\Models\v1\Todo::class, function (Faker\Generator $faker)
{
    return [
        'task'          => $faker->sentence(6),
        'completed_at'  => $faker->optional(0.5)->dateTimeThisYear,
        'todoable_type' => 'reservations',
        'todoable_id'   => $faker->randomElement(App\Models\v1\Reservation::lists('id')->toArray()),
    ];
});

/**
 * Note Factory
 */
$factory->define(App\Models\v1\Note::class, function (Faker\Generator $faker)
{
    return [
        'subject'       => $faker->catchPhrase,
        'content'       => $faker->paragraph(2),
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
    $amount = $faker->randomNumber(4);

    return [
        'name'                 => $faker->sentence(2),
        'description'          => $faker->optional(0.5)->sentence(10),
        'active_at'            => $faker->dateTimeThisYear('+ 6 months'),
        'amount'               => $amount,
        'type'                 => $faker->randomElement(['optional', 'incremental', 'static']),
        'cost_assignable_type' => 'trips',
        'cost_assignable_id'   => $faker->randomElement(App\Models\v1\Trip::lists('id')->toArray()),
    ];
});

/**
 * Payment Factory
 */
$factory->define(App\Models\v1\Payment::class, function (Faker\Generator $faker)
{
    return [
        'cost_id'      => $faker->randomElement(App\Models\v1\Cost::lists('id')->toArray()),
        'percent_owed' => random_int(10, 100),
        'amount_owed'  => $faker->randomNumber(4) / 2,
        'due_at'       => $faker->dateTimeThisYear(),
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
        'item'            => $faker->randomElement(['Passport', 'Medical Release', 'Visa', 'Referral']),
        'due_at'          => $faker->dateTimeThisYear('+ 6 months'),
        'grace_period'    => random_int(0, 10),
        'enforced'        => $faker->boolean(25),
        'requirable_type' => 'trips',
        'requirable_id'   => $faker->randomElement(App\Models\v1\Trip::lists('id')->toArray()),
    ];
});

/**
 * Fundraiser Factory
 */
$factory->define(App\Models\v1\Fundraiser::class, function (Faker\Generator $faker)
{
    return [
        'name'        => $faker->sentence(3),
        'goal_amount' => $faker->numberBetween(1000, 3000),
        'description' => implode('<br />', $faker->paragraphs(3)),
        'expires_at'  => $faker->dateTimeThisYear(),
        'public'      => $faker->boolean(50)
    ];
});

/**
 * Donation Factory
 */
$factory->define(App\Models\v1\Donation::class, function (Faker\Generator $faker)
{
    return [
        'name'                 => $faker->name,
        'amount'               => $faker->numberBetween(1, 1000) * 100,
        'currency'             => $faker->currencyCode,
        'description'          => $faker->sentence(4),
        'message'              => $faker->sentence(5),
        'anonymous'            => $faker->boolean(25),
        'email'                => $faker->safeEmail,
        'phone'                => $faker->phoneNumber,
        'company_name'         => $faker->optional(0.7)->company,
        'address_street'       => $faker->streetAddress,
        'address_city'         => $faker->city,
        'address_state'        => $faker->state,
        'address_zip'          => $faker->postcode,
        'address_country_code' => $faker->countryCode,
        'designation_id'       => $faker->randomElement(App\Models\v1\Fundraiser::lists('id')->toArray()),
        'designation_type'     => 'fundraisers',
        'donor_id'             => $faker->randomElement(App\Models\v1\User::lists('id')->toArray()),
        'donor_type'           => 'users',
        'payment_type'         => 'card',
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
        'user_id'       => $faker->randomElement(App\Models\v1\User::lists('id')->toArray()),
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
        'user_id'      => $faker->randomElement(App\Models\v1\User::lists('id')->toArray()),
    ];
});

/**
 * Contact Factory
 */
$factory->define(App\Models\v1\Contact::class, function (Faker\Generator $faker)
{
    return [
        'user_id'        => $faker->randomElement(App\Models\v1\User::lists('id')->toArray()),
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
$factory->define(App\Models\v1\Medical\Release::class, function (Faker\Generator $faker)
{
    $conditions = [
        [
            "name"       => $faker->randomElement(App\Utilities\v1\Condition::all()),
            "medication" => $faker->sentence(3)
        ],
        [
            "name"       => $faker->randomElement(App\Utilities\v1\Condition::all()),
            "medication" => "none"
        ],
        [
            "name"       => $faker->randomElement(App\Utilities\v1\Condition::all()),
            "medication" => $faker->sentence(3)
        ]
    ];

    $allergies = [
        [
            "name"       => $faker->randomElement(App\Utilities\v1\Allergy::all()),
            "medication" => $faker->sentence(3)
        ],
        [
            "name"       => $faker->randomElement(App\Utilities\v1\Allergy::all()),
            "medication" => "none"
        ],
        [
            "name"       => $faker->randomElement(App\Utilities\v1\Allergy::all()),
            "medication" => $faker->sentence(3)
        ]
    ];

    return [
        'user_id'       => $faker->randomElement(App\Models\v1\User::lists('id')->toArray()),
        'name'          => $faker->firstName . ' ' . $faker->lastName,
        'conditions'    => $faker->randomElements($conditions, 1),
        'allergies'     => $faker->randomElements($allergies, 2),
        'has_insurance' => true,
        'ins_provider'  => $faker->company,
        'ins_policy_no' => $faker->ean8,
        'is_risk'       => $faker->boolean(50)
    ];
});

/**
 * Referral Factory
 */


/**
 * Interaction/Decision Factory
 */
$factory->define(App\Models\v1\Interaction\Decision::class, function (Faker\Generator $faker)
{
    return [
        'team_member_id' => $faker->randomElement(App\Models\v1\TeamMember::lists('id')->toArray()),
        'region_id'      => $faker->randomElement(App\Models\v1\Region::lists('id')->toArray()),
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
        'team_member_id' => $faker->randomElement(App\Models\v1\TeamMember::lists('id')->toArray()),
        'region_id'      => $faker->randomElement(App\Models\v1\Region::lists('id')->toArray()),
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
        'team_member_id'  => $faker->randomElement(App\Models\v1\TeamMember::lists('id')->toArray()),
        'region_id'       => $faker->randomElement(App\Models\v1\Region::lists('id')->toArray()),
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
        'campaign_id'  => $faker->randomElement(App\Models\v1\Campaign::lists('id')->toArray()),
    ];
});

/**
 * Team Factory
 */
$factory->define(App\Models\v1\Team::class, function (Faker\Generator $faker)
{
    return [
        'call_sign'    => $faker->randomNumber(2),
        'region_id'    => $faker->randomElement(App\Models\v1\Region::lists('id')->toArray()),
        'published_at' => $faker->dateTimeThisYear
    ];
});

/**
 * Team Member Factory "Reservation"
 */
$factory->define(App\Models\v1\TeamMember::class, function (Faker\Generator $faker)
{
    return [
        'team_id'         => $faker->randomElement(App\Models\v1\Team::lists('id')->toArray()),
        'assignable_id'   => $faker->randomElement(App\Models\v1\Reservation::lists('id')->toArray()),
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
        'assignable_id'   => $faker->randomElement(App\Models\v1\Assignment::lists('id')->toArray()),
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
        'campaign_id' => $faker->randomElement(App\Models\v1\Campaign::lists('id')->toArray()),
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
        'transport_id'   => $faker->randomElement(App\Models\v1\Transport::lists('id')->toArray()),
        'reservation_id' => $faker->randomElement(App\Models\v1\Reservation::lists('id')->toArray()),
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
        'country_code' => $faker->countryCode,
        'email'        => $faker->optional(0.5)->companyEmail,
        'url'          => $faker->optional(0.5)->url,
        'region_id'    => $faker->randomElement(App\Models\v1\Region::lists('id')->toArray()),
        'short_desc'   => $faker->optional(0.5)->paragraph(3),
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
        'accommodation_id' => $faker->randomElement(App\Models\v1\Accommodation::lists('id')->toArray()),
        'reservation_id'   => $faker->randomElement(App\Models\v1\Reservation::lists('id')->toArray()),
        'room_no'          => $faker->numerify('Rm ##') . ' - ' . $faker->randomElement(['single', 'double']),
        'room_leader'      => $faker->boolean(25)
    ];
});

/**
 * Upload Factory
 */
$factory->define(App\Models\v1\Upload::class, function (Faker\Generator $faker)
{
    return [
        'name' => $faker->userName,
        'source' => $faker->imageUrl(),
        'type' => $faker->randomElement(['photo', 'thumbnail', 'avatar', 'banner', 'file'])
    ];
});


/**
 * Tag Factory
 */
$factory->define(App\Models\v1\Tag::class, function (Faker\Generator $faker)
{
    return [
        'name' => $faker->word,
        'color' => $faker->hexColor
    ];
});