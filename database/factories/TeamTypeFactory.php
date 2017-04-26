<?php

$factory->define(App\Models\v1\TeamType::class, function(Faker\Generator $faker)
{
    return [
        'id'    => $faker->unique()->uuid,
        'name'  => $faker->word,
        'rules' => [
            'min_members' => 25,
            'max_members' => 25,
            'min_leaders' => 2,
            'max_leaders' => 2,
            'min_squads'  => 2,
            'max_squads'  => 10,
            'min_squad_members' => 2,
            'max_squad_members' => 5,
            'min_squad_leaders' => 1,
            'max_squad_leaders' => 1
        ]
    ];
});