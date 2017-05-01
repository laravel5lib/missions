<?php

/**
 * Generic Reservation
 */
$factory->define(App\Models\v1\Reservation::class, function (Faker\Generator $faker)
{
    return [
        'id'                 => $faker->unique()->uuid,
        'given_names'        => $faker->firstName . ' ' . $faker->firstName,
        'surname'            => $faker->lastName,
        'gender'             => $faker->randomElement(['male', 'female']),
        'status'             => $faker->randomElement(['single', 'married']),
        'birthday'           => $faker->dateTimeBetween('-60 years', '-12 years'),
        'shirt_size'         => $faker->randomElement(array_keys(App\Utilities\v1\ShirtSize::all())),
        'desired_role'       => $faker->randomElement(array_keys(App\Utilities\v1\TeamRole::all())),
        'user_id'            => function() {
            return factory(App\Models\v1\User::class)->create()->id;
        },
        'address'            => $faker->address,
        'city'               => $faker->city,
        'zip'                => $faker->postcode,
        'country_code'       => strtolower($faker->countryCode),
        'email'              => $faker->safeEmail,
        'phone_one'          => stripPhone($faker->phoneNumber),
        'phone_two'          => stripPhone($faker->phoneNumber),
        'trip_id'            => function() {
            return factory(App\Models\v1\Trip::class)->create()->id;
        },
        'avatar_upload_id'   => function() {
            return factory(App\Models\v1\Upload::class, 'avatar')->create()->id;
        },
        'created_at' => \Carbon\Carbon::now(),
        'updated_at' => \Carbon\Carbon::now()
    ];
});