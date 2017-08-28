<?php

$factory->define(App\Models\v1\Region::class, function (Faker\Generator $faker) {
    return [
        'id'           => $faker->unique()->uuid,
        'name'         => $faker->city,
        'country_code' => strtolower($faker->countryCode),
        'callsign'     => $faker->word,
        'campaign_id'  => $faker->uuid
    ];
});
