<?php

/**
 * Generic Cost (trip default)
 */
$factory->define(App\Models\v1\Cost::class, function (Faker\Generator $faker)
{
    return [
        'name'                 => $faker->catchPhrase,
        'description'          => $faker->sentence(10),
        'active_at'            => $faker->dateTimeThisYear('+ 6 months'),
        'amount'               => $faker->numberBetween($min = 1000, $max = 3000),
        'type'                 => $faker->randomElement(['incremental', 'static', 'optional']),
        'cost_assignable_type' => 'trips',
        'cost_assignable_id'   => function () {
            return factory(App\Models\v1\Trip::class)->create()->id;
        }
    ];
});

/**
 * Generic Project Cost
 */
$factory->defineAs(App\Models\v1\Cost::class, 'project', function (Faker\Generator $faker) use($factory)
{
    $cost = $factory->raw(App\Models\v1\Cost::class);

    return array_merge($cost, [
        'type'                 => 'static',
        'cost_assignable_type' => 'projects',
        'cost_assignable_id'   => function () {
            return factory(App\Models\v1\Project::class)->create()->id;
        }
    ]);
});

/**
 * Super Early Cost
 */
$factory->defineAs(App\Models\v1\Cost::class, 'super', function (Faker\Generator $faker) use($factory)
{
    $cost = $factory->raw(App\Models\v1\Cost::class);

    return array_merge($cost, [
        'name'        => 'Super Early Registration',
        'description' => 'Discounted cost for early registration and all funds raised early.',
        'active_at'   => '2016-01-01 00:00:00',
        'amount'      => $faker->numberBetween($min = 1000, $max = 1200),
        'type'        => 'incremental',
    ]);
});

/**
 * Early Cost
 */
$factory->defineAs(App\Models\v1\Cost::class, 'early', function (Faker\Generator $faker) use($factory)
{
    $cost = $factory->raw(App\Models\v1\Cost::class);

    return array_merge($cost, [
        'name'                 => 'Early Registration',
        'description'          => 'Discounted cost for early registration and all funds raised early.',
        'active_at'            => '2017-01-01 00:00:00',
        'amount'               => $faker->numberBetween($min = 1300, $max = 1500),
        'type'                 => 'incremental',
    ]);
});

/**
 * General Registration Cost
 */
$factory->defineAs(App\Models\v1\Cost::class, 'general', function (Faker\Generator $faker) use($factory)
{
    $cost = $factory->raw(App\Models\v1\Cost::class);

    return array_merge($cost, [
        'name'                 => 'General Registration',
        'description'          => 'Standard cost to register.',
        'active_at'            => '2017-03-01 00:00:00',
        'amount'               => $faker->numberBetween($min = 1600, $max = 1800),
        'type'                 => 'incremental',
    ]);
});

/**
 * Late Registration Cost
 */
$factory->defineAs(App\Models\v1\Cost::class, 'late', function (Faker\Generator $faker) use($factory)
{
    $cost = $factory->raw(App\Models\v1\Cost::class);

    return array_merge($cost, [
        'name'                 => 'Late Registration',
        'description'          => 'Cost for registering late.',
        'active_at'            => '2017-07-01 00:00:00',
        'amount'               => $faker->numberBetween($min = 1900, $max = 2000),
        'type'                 => 'incremental',
    ]);
});

/**
 * Double Room Request Cost
 */
$factory->defineAs(App\Models\v1\Cost::class, 'double', function (Faker\Generator $faker) use($factory)
{
    $cost = $factory->raw(App\Models\v1\Cost::class);

    return array_merge($cost, [
        'name'                 => 'Double Room Request',
        'description'          => 'Requesting a Double Bed Room (hotel room with two beds for a maximum of two people) for comfort purposes.',
        'active_at'            => '2016-01-01 00:00:00',
        'amount'               => 225,
        'type'                 => 'optional',
    ]);
});

/**
 * Triple Room Request Cost
 */
$factory->defineAs(App\Models\v1\Cost::class, 'triple', function (Faker\Generator $faker) use($factory)
{
    $cost = $factory->raw(App\Models\v1\Cost::class);

    return array_merge($cost, [
        'name'                 => 'Triple Room Request',
        'description'          => 'Requesting a Triple Bed Room (hotel room with two or three beds for a maximum of three people) for comfort purposes.',
        'active_at'            => '2016-01-01 00:00:00',
        'amount'               => 150,
        'type'                 => 'optional',
    ]);
});

/**
 * Deposit Cost
 */
$factory->defineAs(App\Models\v1\Cost::class, 'deposit', function (Faker\Generator $faker) use($factory)
{
    $cost = $factory->raw(App\Models\v1\Cost::class);

    return array_merge($cost, [
        'name'                 => 'Deposit',
        'description'          => 'Non-refundable, non-transferable amount required to secure your initial spot on the trip.',
        'active_at'            => '2016-01-01 00:00:00',
        'amount'               => 100,
        'type'                 => 'static',
    ]);
});

/**
 * Generic Static Cost
 */
$factory->defineAs(App\Models\v1\Cost::class, 'static', function (Faker\Generator $faker) use($factory)
{
    $cost = $factory->raw(App\Models\v1\Cost::class);

    return array_merge($cost, [
        'name'                 => $faker->catchPhrase,
        'active_at'            => '2016-01-01 00:00:00',
        'amount'               => random_int(25000, 100000),
        'type'                 => 'static',
    ]);
});