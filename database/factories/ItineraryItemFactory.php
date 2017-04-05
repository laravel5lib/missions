<?php

$factory->define(App\Models\v1\ItineraryItem::class, function(Faker\Generator $faker)
{
    return [
        'id'           => $faker->unique()->uuid,
        'title'        => $faker->sentence,
        'description'  => $faker->paragraph(3),
        'occures_at'   => Carbon\Carbon::now()->addMonths(6),
        'type'         => 'travel' // travel, event
    ];
});