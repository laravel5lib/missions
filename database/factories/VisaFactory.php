<?php

/**
 * Generic Visa
 */
$factory->define(App\Models\v1\Visa::class, function (Faker\Generator $faker) {
    return [
        'given_names'  => $faker->firstName . ' ' . $faker->firstName,
        'surname'      => $faker->lastName,
        'number'       => $faker->randomNumber(9),
        'issued_at'    => \Carbon\Carbon::yesterday(),
        'expires_at'   => \Carbon\Carbon::now()->addYears(5),
        'country_code' => strtolower($faker->countryCode),
        'user_id'      => function () {
            return factory(App\Models\v1\User::class)->create()->id;
        },
        'upload_id'    => null
    ];
});

/**
 * Expired Visa
 */
$factory->defineAs(App\Models\v1\Visa::class, 'expired', function (Faker\Generator $faker) use ($factory) {
    $visa = $factory->raw(App\Models\v1\Visa::class);

    return array_merge($visa, [
        'issued_at'    => \Carbon\Carbon::now()->subYear(),
        'expires_at'   => \Carbon\Carbon::yesterday(),
    ]);
});
