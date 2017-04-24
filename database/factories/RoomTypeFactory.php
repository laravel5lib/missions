<?php

$factory->define(App\Models\v1\RoomType::class, function(Faker\Generator $faker)
{
    return [
        'id'           => $faker->unique()->uuid,
        'name'         => $faker->word,
        'occupancy'    => random_int(1, 4)
    ];
});