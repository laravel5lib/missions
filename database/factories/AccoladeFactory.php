<?php

/**
 * Countries Visted Accolade
 */
$factory->define(App\Models\v1\Accolade::class, function(Faker\Generator $faker)
{
    $countries = [];

    for ($i = 0; $i <= 10; $i++) {
        array_push($countries, strtolower($faker->countryCode));
    }

    return [
        'id'           => $faker->unique()->uuid,
        'display_name' => 'Countries Visited',
        'name'         => 'countries_visited',
        'items'        => $faker->randomElements($countries, 4)
    ];
});

/**
 * Trip History Accolade
 */
$factory->defineAs(App\Models\v1\Accolade::class, 'trip_history', function(Faker\Generator $faker)
{
    $trips = config('accolades.trips');

    return [
        'id'           => $faker->unique()->uuid,
        'display_name' => 'Trip History',
        'name'         => 'trip_history',
        'items'        => $faker->randomElements($trips, 4)
    ];
});