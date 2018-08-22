<?php

use Faker\Generator as Faker;
use App\Models\v1\Reservation;
use App\Models\v1\MinorRelease;
use App\Models\v1\AirportPreference;
use App\Models\v1\ArrivalDesignation;

$factory->define(AirportPreference::class, function (Faker $faker) {
    return [
        'reservation_id' => function () {
            return factory(Reservation::class)->create()->id;
        },
        'content' => ['AUS', 'DFW', 'IAH']
    ];
});

$factory->define(ArrivalDesignation::class, function (Faker $faker) {
    return [
        'reservation_id' => function () {
            return factory(Reservation::class)->create()->id;
        },
        'content' => ['eastern']
    ];
});

$factory->define(MinorRelease::class, function (Faker $faker) {
    return [
        'reservation_id' => function () {
            return factory(Reservation::class)->create()->id;
        },
        'content' => []
    ];
});
