<?php

$factory->define(App\Models\v1\RoomingPlan::class, function(Faker\Generator $faker)
{
    return [
        'id'          => $faker->unique()->uuid,
        'name'        => $faker->sentence(3),
        'short_desc'  => $faker->paragraph(3),
        'campaign_id' => function() {
            return factory(App\Models\v1\Campaign::class)->create()->id;
        }
    ];
});