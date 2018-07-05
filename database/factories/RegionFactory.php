<?php

use App\Models\v1\Region;
use App\Models\v1\Campaign;

$factory->define(Region::class, function (Faker\Generator $faker) {
    return [
        'id'           => $faker->unique()->uuid,
        'name'         => $faker->city,
        'country_code' => strtolower($faker->countryCode),
        'callsign'     => $faker->word,
        'campaign_id'  => function() {
            return factory(Campaign::class)->create()->id;
        }
    ];
});
