<?php

$factory->define(App\Models\v1\Promotional::class, function(Faker\Generator $faker)
{
    return [
        'id' => $faker->unique()->uuid,
        'name' => $faker->sentence,
        'reward' => 100
    ];
});
