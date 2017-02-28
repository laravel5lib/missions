<?php

/**
 * Generic Group
 */
$factory->define(App\Models\v1\Group::class, function (Faker\Generator $faker)
{
    return [
        'id'               => $faker->unique()->uuid,
        'name'             => $faker->company,
        'type'             => $faker->randomElement(['church', 'business', 'youth', 'nonprofit', 'other']),
        'description'      => $faker->realText(120),
        'timezone'         => $faker->randomElement(\DateTimeZone::listIdentifiers()),
        'address_one'      => $faker->optional(0.5)->streetAddress,
        'address_two'      => $faker->optional(0.5)->buildingNumber,
        'city'             => $faker->optional(0.5)->city,
        'state'            => $faker->optional(0.5)->state,
        'zip'              => $faker->optional(0.5)->postcode,
        'country_code'     => strtolower($faker->countryCode),
        'phone_one'        => stripPhone($faker->phoneNumber),
        'phone_two'        => stripPhone($faker->optional(0.5)->phoneNumber),
        'email'            => $faker->safeEmail,
        'public'           => $faker->boolean(95),
        'avatar_upload_id' => function() {
            return factory(App\Models\v1\Upload::class, 'avatar')->create()->id;
        },
        'banner_upload_id' => function() {
            return factory(App\Models\v1\Upload::class, 'banner')->create()->id;
        },
        'created_at' => \Carbon\Carbon::now(),
        'updated_at' => \Carbon\Carbon::now()
    ];
});

/**
 * Church Group
 */
$factory->defineAs(App\Models\v1\Group::class, 'church', function (Faker\Generator $faker) use ($factory)
{
    $group = $factory->raw(App\Models\v1\Group::class);

    return array_merge($group, [
        'type' => 'church'
    ]);
});

/**
 * Business Group
 */
$factory->defineAs(App\Models\v1\Group::class, 'business', function (Faker\Generator $faker) use ($factory)
{
    $group = $factory->raw(App\Models\v1\Group::class);

    return array_merge($group, [
        'type' => 'business'
    ]);
});

/**
 * Youth Group
 */
$factory->defineAs(App\Models\v1\Group::class, 'youth', function (Faker\Generator $faker) use ($factory)
{
    $group = $factory->raw(App\Models\v1\Group::class);

    return array_merge($group, [
        'type' => 'youth'
    ]);
});

/**
 * Other Group
 */
$factory->defineAs(App\Models\v1\Group::class, 'other', function (Faker\Generator $faker) use ($factory)
{
    $group = $factory->raw(App\Models\v1\Group::class);

    return array_merge($group, [
        'type' => 'other'
    ]);
});
