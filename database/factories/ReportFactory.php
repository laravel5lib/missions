<?php

$factory->define(App\Models\v1\Report::class, function (Faker\Generator $faker) {
    return [
        'id'     => $faker->unique()->uuid,
        'name'   => $faker->sentence,
        'source' => $faker->url,
        'type'   => 'csv'
    ];
});
