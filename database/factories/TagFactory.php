<?php

use Spatie\Tags\Tag;

$factory->define(Tag::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word,
        'type' => $faker->word
    ];
});