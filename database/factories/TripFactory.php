<?php

/**
 * Generic Trip
 */
$factory->define(App\Models\v1\Trip::class, function (Faker\Generator $faker)
{
    return [
        'id'              => $faker->unique()->uuid,
        'group_id'        => function() {
            return factory(App\Models\v1\Group::class)->create()->id;
        },
        'campaign_id'     => function() {
            return factory(App\Models\v1\Campaign::class)->create()->id;
        },
        'spots'           => random_int(10, 500),
        'companion_limit' => random_int(0, 3),
        'country_code'    => function (array $trip) {
            return App\Models\v1\Campaign::find($trip['campaign_id'])->country_code;
        },
        'type'            => $faker->randomElement(['ministry', 'family', 'international', 'leader', 'medical', 'media']),
        'difficulty'      => $faker->randomElement(['level_1', 'level_2', 'level_3']),
        'started_at'      => function (array $trip) {
            return App\Models\v1\Campaign::find($trip['campaign_id'])->started_at;
        },
        'ended_at'        => function (array $trip) {
            return App\Models\v1\Campaign::find($trip['campaign_id'])->ended_at;
        },
        'todos'           => ['Send shirt', 'Send wrist band', 'Enter into LGL', 'Send launch guide', 'Send luggage tag'],
        'team_roles'      => $faker->randomElements(array_keys(App\Utilities\v1\TeamRole::all()), 4),
        'prospects'       => $faker->randomElements([
            'adults', 'teens', 'men', 'women', 'medical professionals',
            'media professionals', 'business professionals', 'pastors',
            'families'], 4),
        'rep_id'           => function() {
            return factory(App\Models\v1\User::class)->create()->id;
        },
        'description'      => file_get_contents(resource_path('assets/sample_trip.md')),
        'public'           => $faker->boolean(95),
        'published_at'     => function (array $trip) {
            return App\Models\v1\Campaign::find($trip['campaign_id'])->published_at->addMonth();
        },
        'closed_at'        => function (array $trip) {
            return App\Models\v1\Campaign::find($trip['campaign_id'])->started_at->subDays(7);
        },
        'created_at' => \Carbon\Carbon::now(),
        'updated_at' => \Carbon\Carbon::now()
    ];
});

/**
 * Ministry trip
 */
$factory->defineAs(App\Models\v1\Trip::class, 'ministry', function (Faker\Generator $faker) use ($factory)
{
    $trip = $factory->raw(App\Models\v1\Trip::class);

    return array_merge($trip, [
        'type' => 'ministry'
    ]);
});

/**
 * Medical trip
 */
$factory->defineAs(App\Models\v1\Trip::class, 'medical', function (Faker\Generator $faker) use ($factory)
{
    $trip = $factory->raw(App\Models\v1\Trip::class);

    return array_merge($trip, [
        'type' => 'medical'
    ]);
});

/**
 * Family trip
 */
$factory->defineAs(App\Models\v1\Trip::class, 'family', function (Faker\Generator $faker) use ($factory)
{
    $trip = $factory->raw(App\Models\v1\Trip::class);

    return array_merge($trip, [
        'type' => 'family'
    ]);
});

/**
 * International trip
 */
$factory->defineAs(App\Models\v1\Trip::class, 'international', function (Faker\Generator $faker) use ($factory)
{
    $trip = $factory->raw(App\Models\v1\Trip::class);

    return array_merge($trip, [
        'type' => 'international'
    ]);
});

/**
 * Media trip
 */
$factory->defineAs(App\Models\v1\Trip::class, 'media', function (Faker\Generator $faker) use ($factory)
{
    $trip = $factory->raw(App\Models\v1\Trip::class);

    return array_merge($trip, [
        'type' => 'media'
    ]);
});

/**
 * Leader trip
 */
$factory->defineAs(App\Models\v1\Trip::class, 'leader', function (Faker\Generator $faker) use ($factory)
{
    $trip = $factory->raw(App\Models\v1\Trip::class);

    return array_merge($trip, [
        'type'       => 'leader',
        'started_at' => function (array $trip) {
            return App\Models\v1\Campaign::find($trip['campaign_id'])->ended_at->subDays(4);
        },
    ]);
});
