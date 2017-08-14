<?php

/**
 * Generic Campaign
 */
$factory->define(App\CampaignTransport::class, function (Faker\Generator $faker) {
    return [
        'type' => $faker->randomElement(['flight', 'train', 'bus', 'vehicle']),
        'name' => $faker->sentence(2),
        'call_sign' => strtoupper($faker->randomLetter().$faker->randomLetter().$faker->randomLetter()),
        'vessel_no' => $faker->randomNumber(4, true),
        'depart_at' => \Carbon\Carbon::today(),
        'arrive_at' => \Carbon\Carbon::tomorrow(),
        'domestic' => false,
        'capacity' => 100,
        'departure_hub_id' => $faker->uuid,
        'arrival_hub_id' => $faker->uuid,
        'campaign_id' => $faker->uuid,
        'designation' => 'outbound'
    ];
});
