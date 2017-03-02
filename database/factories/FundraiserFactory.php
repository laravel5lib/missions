<?php

/**
 * Generic Fundraiser (user sponosor default)
 */
$factory->define(App\Models\v1\Fundraiser::class, function (Faker\Generator $faker)
{
    return [
        'id'               => $faker->unique()->uuid,
        'fund_id'          => function () {
            return factory(App\Models\v1\Fund::class)->create()->id;
        },
        'sponsor_type'     => 'users',
        'sponsor_id'       => function () {
            return factory(App\Models\v1\User::class)->create()->id;
        },
        'name'             => $faker->catchPhrase,
        'type'             => 'general',
        'goal_amount'      => $faker->numberBetween(1000, 3000),
        'description'      => file_get_contents(resource_path('assets/sample_fundraiser.md')),
        'url'              => $faker->unique()->slug(3),
        'started_at'       => \Carbon\Carbon::now(),
        'ended_at'         => function (array $fundraiser) {
            return \Carbon\Carbon::parse($fundraiser['started_at'])->addYear();
        },
        'public'           => true
    ];
});

/**
 * Group Fundraiser
 */
$factory->defineAs(App\Models\v1\Fundraiser::class, 'group', function (Faker\Generator $faker) use ($factory)
{
    $fundraiser = $factory->raw(App\Models\v1\Fundraiser::class);

    return array_merge($fundraiser, [
        'sponsor_type'     => 'groups',
        'sponsor_id'       => function () {
            return factory(App\Models\v1\Group::class)->create()->id;
        }
    ]);
});

/**
 * Private Fundraiser
 */
$factory->defineAs(App\Models\v1\Fundraiser::class, 'private', function (Faker\Generator $faker) use ($factory)
{
    $fundraiser = $factory->raw(App\Models\v1\Fundraiser::class);

    return array_merge($fundraiser, [
        'public' => false
    ]);
});
