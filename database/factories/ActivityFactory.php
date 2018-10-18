<?php

use Carbon\Carbon;
use App\Models\v1\User;
use App\Models\v1\TripInterest;
use Spatie\Activitylog\Models\Activity;

$factory->define(Activity::class, function (Faker\Generator $faker) {
    return [
        'description' => $faker->sentence,
        'subject_type' => 'trip_interests',
        'subject_id' => function() {
            return factory(TripInterest::class)->create()->id;
        },
        'causer_type' => 'users',
        'causer_id' => function() {
            return factory(User::class)->create()->id;
        },
        'created_at' => $this->faker->dateTimeThisMonth(),
        'properties' => ['attributes' => ['status' => 'converted']]
    ];
});
