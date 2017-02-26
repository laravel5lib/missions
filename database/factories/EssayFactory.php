<?php

/*
 * Generic Testimony Essay
 */
$factory->define(App\Models\v1\Essay::class, function(Faker\Generator $faker) {
    return [
        'author_name' => $faker->firstName . ' ' . $faker->lastName,
        'user_id' => function () {
            return factory(App\Models\v1\User::class)->create()->id;
        },
        'subject' => 'Testimony',
        'content' => json_decode(file_get_contents(resource_path('assets/sample_testimony.json')))
    ];
});