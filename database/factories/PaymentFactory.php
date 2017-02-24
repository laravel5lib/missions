<?php

/**
 * Generic Payment
 */
$factory->define(App\Models\v1\Payment::class, function (Faker\Generator $faker)
{
    return [
        'cost_id'      => function () {
            return factory(App\Models\v1\Cost::class)->create()->id;
        },
        'percent_owed' => random_int(10, 100),
        'amount_owed'  => $faker->randomNumber(6),
        'grace_period' => random_int(1, 3),
        'upfront'      => $faker->boolean(50),
        'due_at'       => function (array $payment) use ($faker) {
            return $payment['upfront'] ? null : $faker->dateTimeThisYear;
        }
    ];
});

/**
 * Upfront Payment
 */
$factory->defineAs(App\Models\v1\Payment::class, 'upfront', function (Faker\Generator $faker) use ($factory)
{
    $cost = $factory->raw(App\Models\v1\Payment::class);

    return array_merge($cost, [
        'upfront'      => true,
        'due_at'       => null
    ]);
});
