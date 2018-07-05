<?php

$factory->define(App\Models\v1\Squad::class, function (Faker\Generator $faker) {
    return [
        'region_id' => function () {
            return factory(App\Models\v1\Region::class)->create()->id;
        },
        'callsign'  => $faker->word,
        'published' => false
    ];
});
