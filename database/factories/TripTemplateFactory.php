<?php

use Faker\Generator as Faker;

$factory->define(App\Models\v1\TripTemplate::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence,
        'campaign_id' => function () {
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
        'description' => file_get_contents(resource_path('assets/sample_trip.md')),
        'closed_at'        => function (array $trip) {
            return App\Models\v1\Campaign::find($trip['campaign_id'])->started_at->subDays(7);
        }
    ];
});
