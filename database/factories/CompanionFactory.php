<?php

/**
 * Generic Companion
 */
$factory->define(App\Models\v1\Companion::class, function (Faker\Generator $faker)
{
    return [
        'group_key' => $faker->uuid,
        'reservation_id'           => function () {
            return factory(App\Models\v1\Reservation::class)->create()->id;
        },
        'companion_id' => function () {
            return factory(App\Models\v1\Reservation::class)->create()->id;
        },
        'relationship'             => $faker->randomElement([
            'friend', 'family', 'spouse', 'dependent', 'guardian'
        ])
    ];
});