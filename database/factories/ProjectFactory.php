<?php

/**
 * Generic User Sponsored Project
 */
$factory->define(App\Models\v1\Project::class, function(Faker\Generator $faker)
{
    return [
        'name' => $faker->sentence(3),
        'project_initiative_id' => function () {
            return factory(App\Models\v1\ProjectInitiative::class)->create()->id;
        },
        'rep_id' => function () {
            return factory(App\Models\v1\User::class)->create()->id;
        },
        'sponsor_id' => function () {
            return factory(App\Models\v1\User::class)->create()->id;
        },
        'sponsor_type' => 'users',
        'plaque_prefix' => $faker->randomElement(['in honor of', 'in memory of', 'sponsored by']),
        'plaque_message' => $faker->name
    ];
});

/**
 * Group Sponsored Project
 */
$factory->defineAs(App\Models\v1\Project::class, 'group', function(Faker\Generator $faker) use ($factory)
{
    $project = $factory->raw(App\Models\v1\Project::class);
    
    return array_merge($project, [
        'sponsor_id' => function () {
            return factory(App\Models\v1\Group::class)->create()->id;
        },
        'sponsor_type' => 'groups'
    ]);
});

