<?php

use App\Utilities\v1\TeamRole;

$factory->defineAs(App\Models\v1\RequirementCondition::class, 'role', function (Faker\Generator $faker)
{
    return [
        'id'              => $faker->unique()->uuid,
        'requirement_id'  => $faker->uuid,
        'type'            => 'role',
        'condition'       => $faker->randomElement([
            'limit_to', 
            'except_for', 
            'equal_to', 
            'not_equal_to', 
            'greater_than', 
            'less_than', 
            'greater_than_or_equal_to', 
            'less_than_or_equal_to'
        ]),
        'applies_to'      => $faker->randomElements(array_keys(TeamRole::all()), 6),
        'created_at'      => \Carbon\Carbon::now(),
        'updated_at'      => \Carbon\Carbon::now()
    ];
});