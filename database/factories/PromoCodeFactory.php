<?php

$factory->define(App\Models\v1\PromoCode::class, function(Faker\Generator $faker)
{
    return [
        'id'     => $faker->unique()->uuid,
        'name'   => $faker->sentence,
        'reward' => $faker->integer,
        'code'   => uniqueId(),
        'endorser_id' => $faker->uuid,
        'endorser_type' => 'reservations',
        'expires_at' => Carbon\Carbon::new()->addYear()
    ];
});