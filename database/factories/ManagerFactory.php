<?php

/**
 * Generic Manager
 */
$factory->define(App\Models\v1\Manager::class, function (Faker\Generator $faker)
{
    return [
        'group_id' => function () {
            return factory(App\Models\v1\Group::class)->create()->id;
        },
        'user_id'  => function () {
            return factory(App\Models\v1\User::class)->create()->id;
        }
    ];
});