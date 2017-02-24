<?php

/**
 * Donor Factory
 */
$factory->define(App\Models\v1\Donor::class, function (Faker\Generator $faker)
{
    return [
        'name'         => $faker->firstName . ' '. $faker->lastName,
        'email'        => $faker->safeEmail,
        'phone'        => stripPhone($faker->phoneNumber),
        'company'      => $faker->optional(0.5)->company,
        'zip'          => $faker->postcode,
        'country_code' => strtolower($faker->countryCode),
        'address'      => $faker->streetAddress,
        'city'         => $faker->city,
        'state'        => $faker->state,
    ];
});

/**
 * Donor with user account
 */
$factory->defineAs(App\Models\v1\Donor::class, 'user', function (Faker\Generator $faker) use ($factory)
{
    $donor = $factory->raw(App\Models\v1\Donor::class);

    return array_merge($donor, [
        'account_type' => 'users',
        'account_id' => function () {
            return factory(App\Models\v1\User::class)->create()->id;
        }
    ]);
});

/**
 * Donor with group account
 */
$factory->defineAs(App\Models\v1\Donor::class, 'group', function (Faker\Generator $faker) use ($factory)
{
    $donor = $factory->raw(App\Models\v1\Donor::class);

    return array_merge($donor, [
        'account_type' => 'groups',
        'account_id' => function () {
            return factory(App\Models\v1\Group::class)->create()->id;
        }
    ]);
});
