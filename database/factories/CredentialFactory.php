<?php

use App\Models\v1\User;
use App\Models\v1\Credential;

$factory->defineAs(Credential::class, 'medical', function (Faker\Generator $faker) {
    return [
        'user_id' => function() {
            return factory(User::class)->create()->id;
        },
        'type' => 'medical',
        'applicant_name' => $faker->name,
        'content' => $faker->sentences(4)
    ];
});

$factory->defineAs(Credential::class, 'media', function (Faker\Generator $faker) {
    return [
        'user_id' => function() {
            return factory(User::class)->create()->id;
        },
        'type' => 'media',
        'applicant_name' => $faker->name,
        'content' => $faker->sentences(4)
    ];
});
