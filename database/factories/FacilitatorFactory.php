<?php

/**
 * Generic Facilitator
 */
$factory->define(App\Models\v1\Facilitator::class, function (Faker\Generator $faker)
{
    return [
        'trip_id' => function () {
            return factory(App\Models\v1\Trip::class)->create()->id;
        },
        'user_id' => function () {
            return factory(App\Models\v1\User::class)->create()->id;
        }
    ];
});