<?php

$factory->define(App\Models\v1\PromoCode::class, function(Faker\Generator $faker)
{
    return [
        'id'     => $faker->unique()->uuid,
        'name'   => $faker->sentence,
        'reward' => 10000,
        'campaign_id' => $faker->uuid,
        'expires_at' => Carbon\Carbon::now()->addYear()
    ];
});