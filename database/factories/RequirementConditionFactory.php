<?php

use App\Utilities\v1\TeamRole;

$factory->define(App\Models\v1\RequirementCondition::class, function (Faker\Generator $faker) {
    return [
        'requirement_id'  => $faker->uuid,
        'type'            => $faker->randomElement(config('requirements.conditions.types')),
        'operator'        => $faker->randomElement(config('requirements.conditions.operators')),
        'applies_to'      => $faker->randomElements(array_keys(TeamRole::all()), 6),
        'created_at'      => \Carbon\Carbon::now(),
        'updated_at'      => \Carbon\Carbon::now()
    ];
});
