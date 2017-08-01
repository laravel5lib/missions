<?php

$factory->define(App\Models\v1\Team::class, function (Faker\Generator $faker) {
    return [
        'id'           => $faker->unique()->uuid,
        'callsign'     => $faker->word
    ];
});
