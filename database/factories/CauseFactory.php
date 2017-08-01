<?php

/**
 * Rescue Orphans Cause
 */
$factory->defineAs(App\Models\v1\ProjectCause::class, 'orphans', function (Faker\Generator $faker) {
    return [
        'name' => 'Rescue Orphans',
        'short_desc' => $faker->realText(200),
        'upload_id' => function () {
            return factory(App\Models\v1\Upload::class, 'avatar')->create()->id;
        },
        'countries' => [
            ['code' => 'in', 'name' => 'India'],
            ['code' => 'np', 'name' => 'Nepal']
        ]
    ];
});

/**
 * Clean Water Cause
 */
$factory->defineAs(App\Models\v1\ProjectCause::class, 'water', function (Faker\Generator $faker) {
    return [
        'name' => 'Clean Water',
        'short_desc' => $faker->realText(200),
        'upload_id' => function () {
            return factory(App\Models\v1\Upload::class, 'avatar')->create()->id;
        },
        'countries' => [
            ['code' => 'do', 'name' => 'Dominican Republic'],
            ['code' => 'ni', 'name' => 'Nicaragua']
        ]
    ];
});
