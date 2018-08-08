<?php

/**
 * Generic Medical Release
 */
$factory->define(App\Models\v1\MedicalRelease::class, function (Faker\Generator $faker) {
    return [
        'user_id'       => function () {
            return factory(App\Models\v1\User::class)->create()->id;
        },
        'name'          => $faker->firstName . ' ' . $faker->lastName,
        'ins_provider'  => $faker->company,
        'ins_policy_no' => $faker->ean8,
        'is_risk'       => $faker->boolean(50),
        'emergency_contact' => [
            'name' => $faker->name,
            'email' => $faker->email,
            'phone' => $faker->phoneNumber,
            'relationship' => $faker->randomElement(['friend', 'spouse', 'family', 'guardian', 'other'])
        ]
    ];
});

/**
 * Medical Condition
 */
$factory->define(App\Models\v1\MedicalCondition::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->randomElement(App\Models\v1\MedicalCondition::available()),
        'medication' => $faker->boolean(),
        'diagnosed' => $faker->boolean()
    ];
});

/**
 * Medical Allergy
 */
$factory->define(App\Models\v1\MedicalAllergy::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->randomElement(App\Models\v1\MedicalAllergy::available()),
        'medication' => $faker->boolean(),
        'diagnosed' => $faker->boolean()
    ];
});
