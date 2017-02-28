<?php

/**
 * Generic Deadline (trip default)
 */
$factory->define(App\Models\v1\Deadline::class, function (Faker\Generator $faker)
{
    $trip = App\Models\v1\Trip::inRandomOrder()->first();

    return [
        'id'                       => $faker->unique()->uuid,
        'name'                     => $faker->catchPhrase,
        'grace_period'             => random_int(0, 10),
        'enforced'                 => $faker->boolean(50),
        'deadline_assignable_type' => 'trips',
        'deadline_assignable_id'   => function () {
            return factory(App\Models\v1\Trip::class)->create()->id;
        },
        'date'                     => function (array $deadline) {
            return App\Models\v1\Trip::find($deadline['deadline_assignable_id'])
                       ->started_at
                       ->subDays(random_int(7, 30));
        }
    ];
});

/**
 * Reservation Deadline
 */
$factory->defineAs(App\Models\v1\Deadline::class, 'reservation', function (Faker\Generator $faker) use ($factory)
{
    $deadline = $factory->raw(App\Models\v1\Deadline::class);

    return array_merge($deadline, [
        'deadline_assignable_type' => 'reservations',
        'deadline_assignable_id'   => function () {
            return factory(App\Models\v1\Reservation::class)->create()->id;
        },
        'date'                     => function (array $deadline) {
            return App\Models\v1\Reservation::find($deadline['deadline_assignable_id'])
                       ->trip
                       ->started_at
                       ->subDays(random_int(7, 30));
        }
    ]);
});

/**
 * Project Deadline
 */
$factory->defineAs(App\Models\v1\Deadline::class, 'project', function (Faker\Generator $faker) use ($factory)
{
    $deadline = $factory->raw(App\Models\v1\Deadline::class);

    return array_merge($deadline, [
        'deadline_assignable_type' => 'projects',
        'deadline_assignable_id'   => function () {
            return factory(App\Models\v1\Project::class)->create()->id;
        },
        'date'                     => function (array $deadline) {
            return App\Models\v1\Project::find($deadline['deadline_assignable_id'])
                       ->created_at
                       ->addMonths(random_int(6, 12));
        }
    ]);
});

/**
 * Real Deadline Names
 */
$factory->defineAs(App\Models\v1\Deadline::class, 'real', function (Faker\Generator $faker) use ($factory)
{
    $deadline = $factory->raw(App\Models\v1\Deadline::class);

    return array_merge($deadline, [
        'name' => $faker->randomElement([
            'Last day to add companions', 
            'Team training meetup', 
            'Arrive in Miami'
        ])
    ]);
});

