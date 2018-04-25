<?php

/**
 * Generic Payment
 */
$factory->define(App\Models\v1\Payment::class, function (Faker\Generator $faker) {
    return [
        'price_id'      => function () {
            return factory(App\Models\v1\Price::class)->create()->id;
        },
        'percentage_due' => random_int(10, 100),
        'grace_days'     => random_int(1, 3),
        'due_at'         => $faker->dateTimeThisYear
    ];
});
