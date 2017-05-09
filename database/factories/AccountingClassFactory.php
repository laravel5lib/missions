<?php

$factory->define(App\Models\v1\AccountingClass::class, function(Faker\Generator $faker)
{
    return [
        'id'           => $faker->unique()->uuid,
        'name'         => $faker->sentence,
    ];
});
