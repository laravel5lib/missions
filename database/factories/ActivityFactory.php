<?php

use Carbon\Carbon;
use App\Models\v1\ActivityType;

$factory->define(App\Models\v1\Activity::class, function (Faker\Generator $faker) {
    return [
        'id'                => $faker->unique()->uuid,
        'activity_type_id'  => function () {
            return factory(ActivityType::class)->create()->id;
        },
        'name'              => $faker->sentence(3),
        'description'       => $faker->paragraph(3),
        'participant_id'    => $faker->uuid,
        'participant_type'  => $faker->randomElement(['reservations', 'groups', 'trips', 'campaigns']),
        'occurred_at'        => Carbon::now()->addMonths(6)
    ];
});
