<?php

use App\Models\v1\Squad;
use Faker\Generator as Faker;
use App\Models\v1\Reservation;
use App\Models\v1\SquadMember;

$factory->define(SquadMember::class, function (Faker $faker) {
    return [
        'reservation_id' => function () {
            return factory(Reservation::class)->create()->id;
        },
        'squad_id' => function () {
            return factory(Squad::class)->create()->id;
        },
        'group' => $faker->word
    ];
});
