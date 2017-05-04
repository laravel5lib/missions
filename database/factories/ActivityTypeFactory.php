<?php

$factory->define(App\Models\v1\ActivityType::class, function(Faker\Generator $faker)
{
   return [
        'id'   => $faker->unique()->uuid,
        'name' => $faker->word
    ];
});