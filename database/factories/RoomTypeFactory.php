<?php

$factory->define(App\Models\v1\RoomType::class, function (Faker\Generator $faker) {
    return [
        'id'           => $faker->unique()->uuid,
        'name'         => $faker->word,
        'rules'        => ['occupancy_limit' => random_int(1, 4)],
        'campaign_id'  => $faker->uuid
    ];
});
