<?php

/**
 * Generic Passport
 */
$factory->define(App\Models\v1\Passport::class, function (Faker\Generator $faker) {
    return [
        'given_names'   => $faker->firstName . ' ' . $faker->firstName,
        'surname'       => $faker->lastName,
        'number'        => (string) $faker->randomNumber(9),
        'expires_at'    => \Carbon\Carbon::now()->addYears(10),
        'birth_country' => strtolower($faker->countryCode),
        'citizenship'   => strtolower($faker->countryCode),
        'user_id'       => function () {
            return factory(App\Models\v1\User::class)->create()->id;
        }
    ];
});

/**
 * Expired Passport
 */
$factory->defineAs(App\Models\v1\Passport::class, 'expired', function (Faker\Generator $faker) use ($factory) {
    $passport = $factory->raw(App\Models\v1\Passport::class);

    return array_merge($passport, [
        'expires_at' => \Carbon\Carbon::yesterday(),
    ]);
});
