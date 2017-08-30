<?php

/**
 * Generic User Story
 */
$factory->define(App\Models\v1\Story::class, function(Faker\Generator $faker)
{
    return [
        'title' => $faker->sentence,
        'content' => file_get_contents(resource_path('assets/sample_story.md')),
        'author_type' => 'users',
        'author_id' => $faker->uuid
    ];
});

/**
 * Generic Group Story
 */
$factory->defineAs(App\Models\v1\Story::class, 'group', function(Faker\Generator $faker) use ($factory)
{
    $story = $factory->raw(App\Models\v1\Story::class);

    return array_merge($story, [
        'author_type' => 'groups',
        'author_id' => $faker->uuid
    ]);
});

