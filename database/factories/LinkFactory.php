<?php

/**
 * Generic Link (user default)
 */
$factory->define(App\Models\v1\Link::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word,
        'url' => $faker->url,
        'linkable_type' => 'users',
        'linkable_id' => function () {
            return factory(App\Models\v1\User::class)->create()->id;
        }
    ];
});

/**
 * Group Link
 */
$factory->defineAs(App\Models\v1\Link::class, 'group', function (Faker\Generator $faker) use ($factory) {
    $link = $factory->raw(App\Models\v1\Link::class);

    return array_merge($link, [
        'linkable_type' => 'groups',
        'linkable_id' => function () {
            return factory(App\Models\v1\Group::class)->create()->id;
        }
    ]);
});
