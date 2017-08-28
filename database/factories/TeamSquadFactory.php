<?php

$factory->define(App\Models\v1\TeamSquad::class, function (Faker\Generator $faker) {
    return [
        'id'           => $faker->unique()->uuid,
        'team_id'      => $faker->uuid,
        'callsign'     => $faker->word
    ];
});
