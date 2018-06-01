<?php

use App\Models\v1\Campaign;
use Faker\Generator as Faker;

$factory->define(App\Models\v1\FlightSegment::class, function (Faker $faker) {
    return [
        'name' => $faker->randomElement([
            'Destination Departure', 'Destination Connection', 'Destination Arrival', 
            'Return Departure', 'Return Connection', 'Return Arrival'
        ]),
        'campaign_id' => function () {
            return factory(Campaign::class)->create()->id;
        }
    ];
});
