<?php

/**
 * Generic Reservation Todo
 */
$factory->define(App\Models\v1\Todo::class, function(Faker\Generator $faker) {
    return [
        'task' => $faker->sentence(4),
        'todoable_id' => function () {
            return factory(App\Models\v1\Reservation::class)->create()->id;
        },
        'todoable_type' => 'reservations'
    ];
});

/**
 * Completed Todo
 */
$factory->defineAs(App\Models\v1\Todo::class, 'completed', function(Faker\Generator $faker) use($factory) {
    
    $todo = $factory->raw(App\Models\v1\Todo::class);

    return array_merge($todo, [
        'completed_at' => \Carbon\Carbon::now(),
        'user_id'      => function () {
            return factory(App\Models\v1\User::class)->create()->id;
        }
    ]);
});
