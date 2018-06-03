<?php

use App\Models\v1\Campaign;
use Faker\Generator as Faker;
use App\Models\v1\FlightItinerary;

$factory->define(FlightItinerary::class, function (Faker $faker) {
    return [
        'record_locator' => strtoupper($faker->bothify('?#??#?')),
        'type' => $faker->randomElement(['individual', 'group', 'charter'])
    ];

    // belongs to many reservations
});
