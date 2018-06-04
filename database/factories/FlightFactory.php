<?php

use App\Models\v1\Flight;
use Faker\Generator as Faker;
use App\Models\v1\FlightSegment;
use App\Models\v1\FlightItinerary;

$factory->define(Flight::class, function (Faker $faker) {
    return [
        'flight_segment_id' => function () {
            return factory(FlightSegment::class)->create()->id;
        },
        'flight_itinerary_id' => function () {
            return factory(FlightItinerary::class)->create()->id;
        },
        'flight_no' => $faker->bothify('??####'),
        'date' => $faker->date,
        'time' => $faker->time,
        'iata_code' => $faker->bothify('###')
    ];
});
