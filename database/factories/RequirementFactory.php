<?php

/**
 * Generic Trip Requirement
 */
$factory->define(App\Models\v1\Requirement::class, function (Faker\Generator $faker)
{
    return [
        'id'              => $faker->unique()->uuid,
        'name'            => $faker->randomElement(['Passport', 'Medical Release', 'Visa', 'Referral', 'Testimony']),
        'document_type'   => $faker->randomElement(['passports', 'medical_releases', 'visas', 'referrals', 'essays']),
        'short_desc'      => $faker->realText(120),
        'due_at'          => $faker->dateTimeThisYear('+ 6 months'),
        'grace_period'    => random_int(0, 10),
        'requester_type' => 'trips',
        'requester_id'   => function () {
            return factory(App\Models\v1\Trip::class)->create()->id;
        },
        'created_at' => \Carbon\Carbon::now(),
        'updated_at' => \Carbon\Carbon::now()
    ];
});

/**
 * Passport Requirement
 */
$factory->defineAs(App\Models\v1\Requirement::class, 'passport', function (Faker\Generator $faker) use ($factory)
{
    $requirement = $factory->raw(App\Models\v1\Requirement::class);

    return array_merge($requirement, [
        'name'            => 'Passport',
        'document_type'   => 'passports',
    ]);
});

/**
 * Visa Requirement
 */
$factory->defineAs(App\Models\v1\Requirement::class, 'visa', function (Faker\Generator $faker) use ($factory)
{
    $requirement = $factory->raw(App\Models\v1\Requirement::class);

    return array_merge($requirement, [
        'name'            => 'Visa',
        'document_type'   => 'visas',
    ]);
});

/**
 * Medical Release Requirement
 */
$factory->defineAs(App\Models\v1\Requirement::class, 'medical', function (Faker\Generator $faker) use ($factory)
{
    $requirement = $factory->raw(App\Models\v1\Requirement::class);

    return array_merge($requirement, [
        'name'            => 'Medical Release',
        'document_type'   => 'releases',
    ]);
});

/**
 * Referral Requirement
 */
$factory->defineAs(App\Models\v1\Requirement::class, 'referral', function (Faker\Generator $faker) use ($factory)
{
    $requirement = $factory->raw(App\Models\v1\Requirement::class);

    return array_merge($requirement, [
        'name'            => 'Referral',
        'document_type'   => 'referrals',
    ]);
});

/**
 * Testimony Requirement
 */
$factory->defineAs(App\Models\v1\Requirement::class, 'testimony', function (Faker\Generator $faker) use ($factory)
{
    $requirement = $factory->raw(App\Models\v1\Requirement::class);

    return array_merge($requirement, [
        'name'            => 'Testimony',
        'document_type'   => 'essays',
    ]);
});

/**
 * Airport Preference Requirement
 */
$factory->defineAs(App\Models\v1\Requirement::class, 'airport', function (Faker\Generator $faker) use ($factory)
{
    $requirement = $factory->raw(App\Models\v1\Requirement::class);

    return array_merge($requirement, [
        'name'            => 'Airport Preference',
        'document_type'   => 'airport_preferences',
    ]);
});

/**
 * Arrival Designation Requirement
 */
$factory->defineAs(App\Models\v1\Requirement::class, 'arrival', function (Faker\Generator $faker) use ($factory)
{
    $requirement = $factory->raw(App\Models\v1\Requirement::class);

    return array_merge($requirement, [
        'name'            => 'Arrival Designation',
        'document_type'   => 'arrival_designations',
    ]);
});


