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

$factory->defineAs(App\Models\v1\TeamType::class, 'ministry', function(Faker\Generator $faker)
{
    return [
        'id'    => $faker->unique()->uuid,
        'name'  => 'ministry',
        'rules' => [
            'min_members' => 10,
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

$factory->defineAs(App\Models\v1\TeamType::class, 'medical', function(Faker\Generator $faker)
{
    return [
        'id'    => $faker->unique()->uuid,
        'name'  => 'medical',
        'rules' => [
            'min_members' => 10,
            'max_members' => 25,
            'min_leaders' => 1,
            'max_leaders' => 2,
            'min_squads'  => 1,
            'max_squads'  => 1,
            'min_squad_members' => 10,
            'max_squad_members' => 25,
            'min_squad_leaders' => 0,
            'max_squad_leaders' => 0
        ]
    ];
});

$factory->defineAs(App\Models\v1\TeamType::class, 'leadership', function(Faker\Generator $faker)
{
    return [
        'id'    => $faker->unique()->uuid,
        'name'  => 'leadership',
        'rules' => [
            'min_members' => 2,
            'max_members' => 10,
            'min_leaders' => 2,
            'max_leaders' => 2,
            'min_squads'  => 1,
            'max_squads'  => 1,
            'min_squad_members' => 2,
            'max_squad_members' => 10,
            'min_squad_leaders' => 1,
            'max_squad_leaders' => 1
        ]
    ];
});