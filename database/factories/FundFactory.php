<?php

/**
 * Generic Reservation Fund
 */
$factory->define(App\Models\v1\Fund::class, function (Faker\Generator $faker) {
    $name = $faker->sentence(4);

    return [
        'name' => $name,
        'slug' => str_slug($name),
        'balance' => $faker->randomNumber,
        'fundable_type' => 'reservations',
        'fundable_id' => $faker->uuid,
        'class_id' => function () {
            return factory(App\Models\v1\AccountingClass::class)->create()->id;
        },
        'item_id' => function () {
            return factory(App\Models\v1\AccountingItem::class)->create()->id;
        }
    ];
});

/**
 * Trip Fund
 */
$factory->defineAs(App\Models\v1\Fund::class, 'trip', function (Faker\Generator $faker) use ($factory) {
    $fund = $factory->raw(App\Models\v1\Fund::class);
    
    return array_merge($fund, [
        'fundable_type' => 'trips',
        'fundable_id' => $faker->uuid,
    ]);
});

/**
 * Campaign Fund
 */
$factory->defineAs(App\Models\v1\Fund::class, 'campaign', function (Faker\Generator $faker) use ($factory) {
    $fund = $factory->raw(App\Models\v1\Fund::class);

    return array_merge($fund, [
        'fundable_type' => 'campaigns',
        'fundable_id' => $faker->uuid,
    ]);
});

/**
 * Project Fund
 */
$factory->defineAs(App\Models\v1\Fund::class, 'project', function (Faker\Generator $faker) use ($factory) {
    $fund = $factory->raw(App\Models\v1\Fund::class);

    return array_merge($fund, [
        'fundable_type' => 'projects',
        'fundable_id' => $faker->uuid,
    ]);
});

/**
 * Cause Fund
 */
$factory->defineAs(App\Models\v1\Fund::class, 'cause', function (Faker\Generator $faker) use ($factory) {
    $fund = $factory->raw(App\Models\v1\Fund::class);

    return array_merge($fund, [
        'fundable_type' => 'causes',
        'fundable_id' => $faker->uuid,
    ]);
});
