<?php

$factory->define(App\Models\v1\Room::class, function(Faker\Generator $faker)
{
    return [
        'id'           => $faker->unique()->uuid,
        'room_type_id' => function() {
            return factory(App\Models\v1\RoomType::class)->create()->id;
        },
        'label'        => $faker->word
    ];
});