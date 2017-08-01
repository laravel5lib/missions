<?php

/**
 * Generic User Slug
 */
$factory->define(App\Models\v1\Slug::class, function (Faker\Generator $faker) {
    return [
        'url' => $faker->unique()->slug,
        'slugable_type' => 'users',
        'slugable_id' => function () {
            return factory(App\Models\v1\User::class)->create()->id;
        }
    ];
});

/**
 * Group Slug
 */
$factory->defineAs(App\Models\v1\Slug::class, 'group', function (Faker\Generator $faker) use ($factory) {
    $slug = $factory->raw(App\Models\v1\Slug::class);

    return array_merge($slug, [
        'slugable_type' => 'groups',
        'slugable_id' => function () {
            return factory(App\Models\v1\Group::class)->create()->id;
        }
    ]);
});

/**
 * Campaign Slug
 */
$factory->defineAs(App\Models\v1\Slug::class, 'campaign', function (Faker\Generator $faker) use ($factory) {
    $slug = $factory->raw(App\Models\v1\Slug::class);

    return array_merge($slug, [
        'slugable_type' => 'campaigns',
        'slugable_id' => function () {
            return factory(App\Models\v1\Campaign::class)->create()->id;
        }
    ]);
});
