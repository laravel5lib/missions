<?php

/**
 * Generic Campaign
 */
$factory->define(App\Models\v1\Campaign::class, function (Faker\Generator $faker)
{
    return [
        'id'               => $faker->unique()->uuid,
        'name'             => $faker->catchPhrase,
        'country_code'     => strtolower($faker->countryCode),
        'short_desc'       => $faker->realText(120),
        'page_src'         => '_generic',
        'started_at'       => \Carbon\Carbon::now()->addYear(),
        'ended_at'         => function(array $campaign) {
            return \Carbon\Carbon::parse($campaign['started_at'])->addDays(6);
        },
        'published_at'     => function(array $campaign) {
            return \Carbon\Carbon::parse($campaign['started_at'])->subYear();
        },
        'created_at' => \Carbon\Carbon::now(),
        'updated_at' => \Carbon\Carbon::now()
    ];
});

/**
 * 1Nation1Day 2017
 */
$factory->defineAs(App\Models\v1\Campaign::class, '1n1d2017', function (Faker\Generator $faker) use ($factory)
{
    $campaign = $factory->raw(App\Models\v1\Campaign::class);

    return array_merge($campaign, [
        'name'             => '1Nation1Day 2017',
        'country_code'     => 'ni',
        'short_desc'       => '1Nation1Day Nicaragua will be the largest global missions outreach in history. But this isn’t just about numbers; it\'s about creating measurable change. It takes an unprecedented strategy to make this audacious vision a reality.',
        'page_src'         => '_1n1d2017',
        'started_at'       => '2017-07-22 00:00:00',
        'ended_at'         => '2017-07-30 22:59:59',
        'published_at'     => '2016-01-01 00:00:00'
    ]);
});

/**
 * Orphans to Angles 2017
 */
$factory->defineAs(App\Models\v1\Campaign::class, 'india', function (Faker\Generator $faker) use ($factory)
{
    $campaign = $factory->raw(App\Models\v1\Campaign::class);

    return array_merge($campaign, [
        'name'             => 'Orphans to Angels',
        'country_code'     => 'in',
        'short_desc'       => 'Participate in the emotional dedications of several orphanages, each permanently houses 12-50 orphans. Spend the day at each "Angel House" loving on each kid who may have never been hugged or loved on in their entire lives.',
        'page_src'         => '_india',
        'started_at'       => '2017-06-03 00:00:00',
        'ended_at'         => '2017-06-11 22:59:59',
        'published_at'     => '2017-01-01 00:00:00'
    ]);
});