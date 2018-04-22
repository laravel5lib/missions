<?php

use App\Models\v1\Cost;

/**
 * Generic Price (trip default)
 */
$factory->define(App\Models\v1\Price::class, function (Faker\Generator $faker) {
    return [
        'cost_id' => function() {
            return factory(Cost::class)->create()->id;
        },
        'active_at' => $faker->dateTimeThisYear('+ 6 months'),
        'amount' => $faker->numberBetween($min = 1000, $max = 3000),
        'model_type' => 'trips',
        'model_id' => $faker->uuid
    ];
});
