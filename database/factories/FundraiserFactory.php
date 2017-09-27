<?php

/**
 * Generic Fundraiser (user sponosor default)
 */
$factory->define(App\Models\v1\Fundraiser::class, function (Faker\Generator $faker) {
    return [
        'id'               => $faker->unique()->uuid,
        'fund_id'          => $faker->uuid,
        'sponsor_type'     => 'users',
        'sponsor_id'       => $faker->uuid,
        'name'             => $faker->catchPhrase,
        'type'             => 'general',
        'goal_amount'      => $faker->numberBetween(1000, 3000),
        'description'      => file_get_contents(resource_path('assets/sample_fundraiser.md')),
        'url'              => $faker->unique()->slug(3),
        'started_at'       => \Carbon\Carbon::now(),
        'ended_at'         => \Carbon\Carbon::now()->addYear(),
        'public'           => true
    ];
});

/**
 * Group Fundraiser
 */
$factory->defineAs(App\Models\v1\Fundraiser::class, 'group', function (Faker\Generator $faker) use ($factory) {
    $fundraiser = $factory->raw(App\Models\v1\Fundraiser::class);

    return array_merge($fundraiser, [
        'sponsor_type'     => 'groups',
        'sponsor_id'       => $faker->uuid
    ]);
});

/**
 * Private Fundraiser
 */
$factory->defineAs(App\Models\v1\Fundraiser::class, 'private', function (Faker\Generator $faker) use ($factory) {
    $fundraiser = $factory->raw(App\Models\v1\Fundraiser::class);

    return array_merge($fundraiser, [
        'public' => false
    ]);
});
