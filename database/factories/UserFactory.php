<?php

/**
 * Generic User
 */
$factory->define(App\Models\v1\User::class, function (Faker\Generator $faker) {
    $password = bcrypt('secret');

    return [
        'id'               => $faker->unique()->uuid,
        'first_name'       => $faker->firstName,
        'last_name'        => $faker->lastName,
        'email'            => $faker->unique()->safeEmail,
        'alt_email'        => $faker->optional(0.5)->safeEmail,
        'password'         => $password,
        'gender'           => $faker->randomElement(['male', 'female']),
        'status'           => $faker->randomElement(['single', 'married']),
        'birthday'         => $faker->dateTimeBetween('-60 years', '-12 years'),
        'phone_one'        => stripPhone($faker->optional(0.5)->phoneNumber),
        'phone_two'        => stripPhone($faker->optional(0.5)->phoneNumber),
        'address'          => $faker->optional(0.5)->streetAddress,
        'city'             => $faker->optional(0.5)->city,
        'state'            => $faker->optional(0.5)->state,
        'zip'              => $faker->optional(0.5)->postcode,
        'country_code'     => $faker->randomElement(explode(',', App\Utilities\v1\Country::codes())),
        // 'timezone'         => $faker->randomElement(\DateTimeZone::listIdentifiers()),
        'timezone'         => 'America/Detroit',
        'bio'              => $faker->optional(0.5)->realText(120),
        'public'           => $faker->boolean(50),
        'remember_token'   => str_random(10),
        'avatar_upload_id' => null,
        'banner_upload_id' => null,
        'created_at' => \Carbon\Carbon::now(),
        'updated_at' => \Carbon\Carbon::now()
    ];
});

/**
 * Admin User
 */
$factory->defineAs(App\Models\v1\User::class, 'admin', function (Faker\Generator $faker) use ($factory) {
    $user = $factory->raw(App\Models\v1\User::class);

    return array_merge($user, [
        'email'    => 'admin@admin.com'
    ]);
});

/**
 * New User
 */
$factory->defineAs(App\Models\v1\User::class, 'new', function (Faker\Generator $faker) {
    return [
        'id'               => $faker->unique()->uuid,
        'first_name'       => $faker->firstName,
        'last_name'        => $faker->lastName,
        'email'            => $faker->unique()->safeEmail,
        'password'         => bcrypt('secret'),
        'gender'           => $faker->randomElement(['male', 'female']),
        'status'           => $faker->randomElement(['single', 'married']),
        'birthday'         => $faker->dateTimeBetween('-60 years', '-12 years'),
        'country_code'     => $faker->randomElement(explode(',', App\Utilities\v1\Country::codes())),
        'timezone'         => 'America/Detroit',
        'created_at'       => \Carbon\Carbon::now(),
        'updated_at'       => \Carbon\Carbon::now()
    ];
});
