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
        'gender'           => $faker->optional(0.5)->randomElement(['male', 'female']),
        'status'           => $faker->optional(0.5)->randomElement(['single', 'married']),
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
        'url'              => str_slug($name),
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
        'passport_id'        => $faker->randomElement(App\Models\v1\Passport::lists('id')->toArray()),
        'visa_id'            => $faker->randomElement(App\Models\v1\Visa::lists('id')->toArray()),
        'medical_release_id' => $faker->randomElement(App\Models\v1\MedicalRelease::lists('id')->toArray()),
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
        'type'            => $faker->randomElement(['full', 'short', 'medical', 'media']),
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
$factory->defineAs(App\Models\v1\Campaign::class, 'active', function (Faker\Generator $faker)
{
    $startDate = $faker->dateTimeBetween('+6 months', '+1 year');

    return [
        'name'             => $faker->catchPhrase,
        'country_code'     => strtolower($faker->countryCode),
        'short_desc'       => $faker->realText(255),
        'page_url'         => $faker->slug,
        'started_at'       => $startDate,
        'ended_at'         => $faker->dateTimeInInterval($startDate, '+ 10 days'),
        'published_at'     => $faker->dateTimeBetween('- 1 month', '+ 1 month'),
        'avatar_upload_id' => $faker->randomElement(\App\Models\v1\Upload::where('type', 'avatar')->lists('id')->toArray()),
        'banner_upload_id' => $faker->randomElement(\App\Models\v1\Upload::where('type', 'banner')->lists('id')->toArray())
    ];
});

$factory->defineAs(App\Models\v1\Campaign::class, 'archived', function (Faker\Generator $faker)
{
    $startDate = $faker->dateTimeBetween('- 1 year', '- 6 months');

    return [
        'name'             => $faker->catchPhrase,
        'country_code'     => strtolower($faker->countryCode),
        'short_desc'       => $faker->realText(255),
        'page_url'         => $faker->slug,
        'started_at'       => $startDate,
        'ended_at'         => $faker->dateTimeInInterval($startDate, '+ 10 days'),
        'published_at'     => $faker->dateTimeBetween('- 1 year', '- 6 months'),
        'avatar_upload_id' => $faker->randomElement(\App\Models\v1\Upload::where('type', 'avatar')->lists('id')->toArray()),
        'banner_upload_id' => $faker->randomElement(\App\Models\v1\Upload::where('type', 'banner')->lists('id')->toArray())
    ];
});

/**
 * Group Factory
 */
$factory->define(App\Models\v1\Group::class, function (Faker\Generator $faker)
{
    return [
        'name'             => $faker->company,
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
        'public'           => $faker->boolean(50),
        'url'              => $faker->userName,
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
 * Todo Factory
 */
$factory->define(App\Models\v1\Todo::class, function (Faker\Generator $faker)
{
    return [
        'task'          => $faker->realText(25),
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
$factory->defineAs(App\Models\v1\Cost::class, 'incremental', function (Faker\Generator $faker)
{
    return [
        'name'                 => $faker->catchPhrase,
        'description'          => $faker->optional(0.5)->sentence(10),
        'active_at'            => $faker->dateTimeThisYear('+ 6 months'),
        'amount'               => $faker->numberBetween($min = 1000, $max = 3000),
        'type'                 => 'incremental',
        'cost_assignable_type' => 'trips',
        'cost_assignable_id'   => $faker->randomElement(App\Models\v1\Trip::lists('id')->toArray()),
    ];
});

$factory->defineAs(App\Models\v1\Cost::class, 'static', function (Faker\Generator $faker)
{
    return [
        'name'                 => $faker->catchPhrase,
        'description'          => $faker->optional(0.5)->sentence(10),
        'active_at'            => $faker->dateTimeThisYear(),
        'amount'               => $faker->numberBetween($min = 50, $max = 200),
        'type'                 => 'static',
        'cost_assignable_type' => 'trips',
        'cost_assignable_id'   => $faker->randomElement(App\Models\v1\Trip::lists('id')->toArray()),
    ];
});

$factory->defineAs(App\Models\v1\Cost::class, 'optional', function (Faker\Generator $faker)
{
    return [
        'name'                 => $faker->catchPhrase,
        'description'          => $faker->optional(0.5)->sentence(10),
        'active_at'            => $faker->dateTimeThisYear('+ 6 months'),
        'amount'               => $faker->numberBetween($min = 100, $max = 500),
        'type'                 => 'optional',
        'cost_assignable_type' => 'trips',
        'cost_assignable_id'   => $faker->randomElement(App\Models\v1\Trip::lists('id')->toArray()),
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
        'name'             => $faker->catchPhrase,
        'type'             => $faker->randomElement(['general', 'envelope']),
        'goal_amount'      => $faker->numberBetween(1000, 3000),
        'description'      => file_get_contents(resource_path('assets/sample_fundraiser.md')),
        'url'              => $faker->unique()->slug(3),
        'started_at'       => $faker->dateTimeThisYear(),
        'ended_at'         => $faker->dateTimeThisYear(),
        'public'           => $faker->boolean(50),
        'banner_upload_id' => $faker->randomElement(\App\Models\v1\Upload::pluck('id')->toArray())
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

$factory->define(App\Models\v1\Fund::class, function(Faker\Generator $faker)
{
    return [
        'name' => $faker->sentence(4),
        'balance' => $faker->randomNumber,
        'fundable_id' => $faker->randomElement(App\Models\v1\Reservation::pluck('id')->toArray()),
        'fundable_type' => 'reservations'
    ];
});

$factory->defineAs(App\Models\v1\Transaction::class, 'donation', function(Faker\Generator $faker)
{
    $fund = $faker->randomElement(App\Models\v1\Fund::get()->toArray());
    $donor = $faker->randomElement(App\Models\v1\Donor::get()->toArray());

    return [
        'fund_id' => $fund['id'],
        'donor_id' => $donor['id'],
        'description' => 'Donation to ' . $fund['name'],
        'comment' => $faker->realText($maxNbChars = 120, $indexSize = 2),
        'type' => 'donation',
        'amount' => $faker->randomNumber(2),
        'payment' => [
            'type' => 'card',
            'last_four' => substr($faker->creditCardNumber, -4),
            'cardholder' => $faker->name,
            'zip' => $faker->postcode,
            'brand' => $faker->creditCardType
        ],
        'created_at' => $faker->randomElement(App\Models\v1\Fundraiser::pluck('started_at')->toArray()),
    ];
});

$factory->defineAs(App\Models\v1\Transaction::class, 'anonymous', function(Faker\Generator $faker)
{
    $fund = $faker->randomElement(App\Models\v1\Fund::all()->toArray());

    return [
        'fund_id' => $fund['id'],
        'donor_id' => App\Models\v1\Donor::where('name', 'anonymous')->pluck('id'),
        'description' => 'Donation to ' . $fund['name'],
        'type' => 'donation',
        'amount' => $faker->randomNumber(2),
        'payment' => [
            'type' => 'card',
            'last_four', '1234',
            'cardholder' => 'John Doe',
            'zip' => '56789',
            'brand' => 'visa'
        ],
        'created_at' => $faker->dateTimeThisYear
    ];
});

$factory->defineAs(App\Models\v1\Transaction::class, 'transfer_from', function(Faker\Generator $faker)
{
    $fund = $faker->randomElement(App\Models\v1\Fund::all()->toArray());

    return [
        'fund_id' => $fund['id'],
        'description' => 'Transfer from ' . $fund['name'],
        'donor_id' => null,
        'type' => 'transfer',
        'amount' => -$faker->randomNumber(2),
        'payment' => null,
    ];
});

$factory->defineAs(App\Models\v1\Transaction::class, 'transfer_to', function(Faker\Generator $faker)
{
    $fund = $faker->randomElement(App\Models\v1\Fund::all()->toArray());

    return [
        'fund_id' => $fund['id'],
        'description' => 'Transfer to ' . $fund['name'],
        'donor_id' => null,
        'type' => 'transfer',
        'amount' => $faker->randomNumber(2),
        'payment' => null,
    ];
});

/**
 * Trip Interest Factory
 */
$factory->define(App\Models\v1\TripInterest::class, function(Faker\Generator $faker) {
    return [
        'trip_id' => $faker->randomElement(App\Models\v1\Trip::pluck('id')->toArray()),
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'phone' => $faker->optional(0.5)->phoneNumber,
        'communication_preferences' => $faker->randomElements(['email', 'phone', 'text'], 2)
    ];
});

/**
 * Cause Factory
 */
$factory->define(App\Models\v1\Cause::class, function(Faker\Generator $faker) {
    return [
        'name' => $faker->catchPhrase,
        'short_desc' => $faker->realText(200),
        'upload_id' => $faker->randomElement(App\Models\v1\Upload::pluck('id')->toArray()),
        'active' => $faker->boolean(50)
    ];
});

/**
 * Project Type Factory
 */
$factory->define(App\Models\v1\ProjectType::class, function(Faker\Generator $faker) {
    return [
        'name' => $faker->word,
        'short_desc' => $faker->realText(200),
        'upload_id' => $faker->randomElement(App\Models\v1\Upload::pluck('id')->toArray()),
        'active' => $faker->boolean(50)
    ];
});

/**
 * Project Initiative Factory
 */
$factory->define(App\Models\v1\ProjectInitiative::class, function(Faker\Generator $faker) {
    return [
        'name' => $faker->catchPhrase,
        'cause_id' => $faker->randomElement(App\Models\v1\Cause::pluck('id')->toArray()),
        'country_code' => $faker->countryCode,
        'started_at' => $faker->dateTimeThisYear(),
        'ended_at' => $faker->dateTimeThisYear(),
        'active' => $faker->boolean(50),
        'rep_id' => $faker->randomElement(App\Models\v1\User::pluck('id')->toArray())
    ];
});

/**
 * Project Package Factory
 */
$factory->define(App\Models\v1\ProjectPackage::class, function(Faker\Generator $faker) {
    return [
        'generate_dates' => $faker->boolean(50),
        'project_type_id' => $faker->randomElement(App\Models\v1\ProjectType::pluck('id')->toArray()),
        'project_initiative_id' => $faker->randomElement(App\Models\v1\ProjectInitiative::pluck('id')->toArray()),
    ];
});

/**
 * Project Factory
 */
$factory->define(App\Models\v1\Project::class, function(Faker\Generator $faker) {
    return [
        'project_package_id' => $faker->randomElement(App\Models\v1\ProjectPackage::pluck('id')->toArray()),
        'rep_id' => $faker->randomElement(App\Models\v1\User::pluck('id')->toArray()),
        'sponsor_id' => $faker->randomElement(App\Models\v1\User::pluck('id')->toArray()),
        'sponsor_type' => 'users',
        'plaque_prefix' => $faker->randomElement(['in honor of', 'in memory of', 'sponsored by']),
        'plaque_message' => $faker->name,
        'launched_at' => $faker->dateTimeThisYear('+ 1 year')
    ];
});
