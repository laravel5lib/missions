<?php

$factory->define(App\Models\v1\Representative::class, function (Faker\Generator $faker) {
    return [
        'name'         => $faker->name,
        'email'        => $faker->email,
        'phone'        => $faker->isbn10,
        'ext'          => $faker->randomNumber(4, true),
        'avatar_url'   => $faker->imageUrl()
    ];
});
