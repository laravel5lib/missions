<?php

/**
 * Avatar Upload
 */
$factory->defineAs(App\Models\v1\Upload::class, 'avatar', function (Faker\Generator $faker)
{
    return [
        'name' => $faker->word,
        'source' => $faker->randomElement([
            'images/avatars/1n1d17-dark-400x400.jpg',
            'images/avatars/1n1d17-red-400x400.jpg',
            'images/avatars/1n1d17-white-400x400.jpg'
        ]),
        'type' => 'avatar'
    ];
});

/**
 * Banner Upload
 */
$factory->defineAs(App\Models\v1\Upload::class, 'banner', function (Faker\Generator $faker)
{
    return [
        'id' => $faker->unique()->uuid,
        'name' => $faker->word,
        'source' => $faker->randomElement([
            'images/banners/1n1d17-missionary-2560x800.jpg',
            'images/banners/1n1d17-speak-2560x800.jpg',
            'images/banners/1n1d17-vision-2560x800.jpg',
            'images/banners/1n1d17-vision2-2560x800.jpg',
            'images/banners/1n1d17-vision3-2560x800.jpg',
            'images/banners/1n1d17-water-2560x800.jpg'
        ]),
        'type' => 'banner'
    ];
});

/**
 * Passport Upload
 */
$factory->defineAs(App\Models\v1\Upload::class, 'passport', function (Faker\Generator $faker)
{
    return [
        'id' => $faker->unique()->uuid,
        'name' => 'passport_' . $faker->word,
        'source' => 'images/other/fake-passport.jpg',
        'type' => 'other'
    ];
});

/**
 * Visa Upload
 */
$factory->defineAs(App\Models\v1\Upload::class, 'visa', function (Faker\Generator $faker)
{
    return [
        'id' => $faker->unique()->uuid,
        'name' => 'visa_' . $faker->word,
        'source' => 'images/other/fake-visa.jpg',
        'type' => 'other'
    ];
});


