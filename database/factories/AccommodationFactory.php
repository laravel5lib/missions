<?php

$factory->define(App\Models\v1\Accommodation::class, function(Faker\Generator $faker)
{

    return [
        'id'           => $faker->unique()->uuid,
        'name'         => $faker->sentence,
        'region_id'    => $faker->uuid,
        'address_one'  => $faker->optional(0.5)->streetAddress,
        'address_two'  => null,
        'city'         => $faker->optional(0.5)->city,
        'state'        => $faker->optional(0.5)->state,
        'zip'          => $faker->optional(0.5)->postcode,
        'phone'        => stripPhone($faker->optional(0.5)->phoneNumber),
        'fax'          => stripPhone($faker->optional(0.5)->phoneNumber),
        'country_code' => strtolower($faker->countryCode),
        'email'        => $faker->optional(0.5)->safeEmail,
        'url'          => $faker->optional(0.5)->url,
        'short_desc'   => $faker->optional(0.5)->paragraph(3)
    ];
});
