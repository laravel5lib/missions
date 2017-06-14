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
            'min_groups'  => 1,
            'max_groups'  => 10,
            'min_group_members' => 2,
            'max_group_members' => 5,
            'min_group_leaders' => 1,
            'max_group_leaders' => 1
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
            'min_groups'  => 2,
            'max_groups'  => 10,
            'min_group_members' => 2,
            'max_group_members' => 5,
            'min_group_leaders' => 1,
            'max_group_leaders' => 1
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
            'min_groups'  => 1,
            'max_groups'  => 1,
            'min_group_members' => 10,
            'max_group_members' => 25,
            'min_group_leaders' => 0,
            'max_group_leaders' => 0
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
            'min_groups'  => 1,
            'max_groups'  => 1,
            'min_group_members' => 2,
            'max_group_members' => 10,
            'min_group_leaders' => 1,
            'max_group_leaders' => 1
        ]
    ];
});