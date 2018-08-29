<?php

use App\Models\v1\User;
use App\Models\v1\MedicalCredential;
use App\Models\v1\MediaCredential;

$factory->define(MedicalCredential::class, function (Faker\Generator $faker) {
    return [
        'user_id' => function() {
            return factory(User::class)->create()->id;
        },
        'type' => 'medical',
        'applicant_name' => $faker->name,
        'content' => $faker->sentences(4)
    ];
});

$factory->define(MediaCredential::class, function (Faker\Generator $faker) {
    return [
        'user_id' => function() {
            return factory(User::class)->create()->id;
        },
        'type' => 'media',
        'applicant_name' => $faker->name,
        'content' => $faker->sentences(4)
    ];
});
