<?php

/**
 * Generic Trip Interest
 */
$factory->define(App\Models\v1\TripInterest::class, function(Faker\Generator $faker) {
    return [
        'id' => $faker->unique()->uuid,
        'trip_id' => $faker->uuid,
        'status' => 'undecided',
        'name' => $faker->firstName. ' ' .$faker->lastName,
        'email' => $faker->safeEmail,
        'phone' => $faker->optional(0.5)->phoneNumber,
        'communication_preferences' => $faker->randomElements(['email', 'phone', 'text'], 2),
        'created_at' => \Carbon\Carbon::now(),
        'updated_at' => \Carbon\Carbon::now()
    ];
});

/**
 * Converted Trip Interest
 */
$factory->defineAs(App\Models\v1\TripInterest::class, 'converted', function(Faker\Generator $faker) use ($factory)
{
    $interest = $factory->raw(App\Models\v1\TripInterest::class);

    return array_merge($interest, [
        'status' => 'converted'
    ]);
});

/**
 * Declined Trip Interest
 */
$factory->defineAs(App\Models\v1\TripInterest::class, 'declined', function(Faker\Generator $faker) use ($factory)
{
    $interest = $factory->raw(App\Models\v1\TripInterest::class);

    return array_merge($interest, [
        'status' => 'declined'
    ]);
});

