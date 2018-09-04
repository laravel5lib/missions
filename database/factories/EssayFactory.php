<?php

/*
 * Generic Testimony Essay
 */
$factory->define(App\Models\v1\Essay::class, function (Faker\Generator $faker) {
    return [
        'author_name' => $faker->firstName . ' ' . $faker->lastName,
        'user_id' => $faker->uuid,
        'subject' => 'Testimony',
        'content' => json_decode(file_get_contents(resource_path('assets/sample_testimony.json')))
    ];
});

$factory->define(App\Models\v1\InfluencerApplication::class, function (Faker\Generator $faker) {
    return [
        'author_name' => $faker->firstName . ' ' . $faker->lastName,
        'user_id' => $faker->uuid,
        'subject' => 'Influencer',
        'content' => json_decode(file_get_contents(resource_path('assets/sample_testimony.json')))
    ];
});
