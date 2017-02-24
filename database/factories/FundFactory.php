<?php

/**
 * Generic Reservation Fund
 */
$factory->define(App\Models\v1\Fund::class, function(Faker\Generator $faker)
{
    $name = $faker->sentence(4);

    return [
        'name' => $name,
        'slug' => str_slug($name),
        'balance' => $faker->randomNumber,
        'fundable_type' => 'reservations',
        'fundable_id' => function () {
            return factory(App\Models\v1\Reservation::class)->create()->id;
        },
        'class' => function (array $fund) {
            return App\Models\v1\Reservation::find($fund['fundable_id'])->trip->campaign->name . ' - Team';
        },
        'item' => 'Missionary Donation'
    ];
});

/**
 * Trip Fund
 */
$factory->defineAs(App\Models\v1\Fund::class, 'trip', function(Faker\Generator $faker) use ($factory)
{   
    $fund = $factory->raw(App\Models\v1\Fund::class);
    
    return array_merge($fund, [
        'fundable_type' => 'trips',
        'fundable_id' => function () {
            return factory(App\Models\v1\Trip::class)->create()->id;
        },
        'class' => function (array $fund) {
            return App\Models\v1\Trip::find($fund['fundable_id'])->campaign->name . '- Team';
        },
        'item' => 'Missionary Donation'
    ]);
});

/**
 * Campaign Fund
 */
$factory->defineAs(App\Models\v1\Fund::class, 'campaign', function(Faker\Generator $faker) use ($factory)
{   
    $fund = $factory->raw(App\Models\v1\Fund::class);

    return array_merge($fund, [
        'fundable_type' => 'campaigns',
        'fundable_id' => function () {
            return factory(App\Models\v1\Campaign::class)->create()->id;
        },
        'class' => function (array $fund) {
            return App\Models\v1\Campaign::find($fund['fundable_id'])->name;
        },
        'item' => 'General Donation'
    ]);
});

/**
 * Project Fund
 */
$factory->defineAs(App\Models\v1\Fund::class, 'project', function(Faker\Generator $faker) use ($factory)
{   
    $fund = $factory->raw(App\Models\v1\Fund::class);

    return array_merge($fund, [
        'fundable_type' => 'projects',
        'fundable_id' => function () {
            return factory(App\Models\v1\Project::class)->create()->id;
        },
        'class' => function (array $fund) {
            return str_plural(App\Models\v1\Project::find($fund['fundable_id'])->initiative->cause->name);
        },
        'item' => function (array $fund) {
            $project = App\Models\v1\Project::find($fund['fundable_id']);
            return $project->name . ' - ' . $project->initiative->cause->name;
        }
    ]);
});

/**
 * Cause Fund
 */
$factory->defineAs(App\Models\v1\Fund::class, 'cause', function(Faker\Generator $faker) use ($factory)
{   
    $fund = $factory->raw(App\Models\v1\Fund::class);

    return array_merge($fund, [
        'fundable_type' => 'causes',
        'fundable_id' => function () {
            return factory(App\Models\v1\ProjectCause::class)->create()->id;
        },
        'class' => function (array $fund) {
            return str_plural(App\Models\v1\ProjectCause::find($fund['fundable_id'])->name);
        },
        'item' => 'General Donation'
    ]);
});

