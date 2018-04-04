<?php

use App\Models\v1\Reminder;
use App\Models\v1\Fundraiser;

$factory->define(Reminder::class, function (Faker\Generator $faker) {
    return [
        'email' => $faker->email,
        'remindable_id' => function () {
            return factory(Fundraiser::class)->create()->id;
        },
        'remindable_type' => 'fundraisers'
    ];
});
