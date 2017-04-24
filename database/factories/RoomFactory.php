<?php

$factory->define(App\Models\v1\Room::class, function(Faker\Generator $faker)
{
    return [
        'id'           => $faker->unique()->uuid,
        'room_type_id' => $faker->uuid,
        'label'        => $faker->word
    ];
});