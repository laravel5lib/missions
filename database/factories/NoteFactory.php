<?php

/**
 * Generic Note (reservation default)
 */
$factory->define(App\Models\v1\Note::class, function (Faker\Generator $faker) {
    return [
        'id'            => $faker->unique()->uuid,
        'subject'       => $faker->catchPhrase,
        'content'       => $faker->realText(120),
        'user_id'       => function () {
            return factory(App\Models\v1\User::class)->create()->id;
        },
        'noteable_type' => 'reservations',
        'noteable_id'   => function () {
            return factory(App\Models\v1\Reservation::class)->create()->id;
        },
        'created_at' => \Carbon\Carbon::now(),
        'updated_at' => \Carbon\Carbon::now()
    ];
});

/**
 * Group Note
 */
$factory->defineAs(App\Models\v1\Note::class, 'group', function (Faker\Generator $faker) use ($factory) {
    $note = $factory->raw(App\Models\v1\Note::class);

    return array_merge($note, [
        'noteable_type' => 'groups',
        'noteable_id'   => function () {
            return factory(App\Models\v1\Group::class)->create()->id;
        }
    ]);
});

/**
 * Trip Note
 */
$factory->defineAs(App\Models\v1\Note::class, 'trip', function (Faker\Generator $faker) use ($factory) {
    $note = $factory->raw(App\Models\v1\Note::class);
    
    return array_merge($note, [
        'noteable_type' => 'trips',
        'noteable_id'   => function () {
            return factory(App\Models\v1\Trip::class)->create()->id;
        }
    ]);
});

/**
 * Project Note
 */
$factory->defineAs(App\Models\v1\Note::class, 'project', function (Faker\Generator $faker) use ($factory) {
    $note = $factory->raw(App\Models\v1\Note::class);
    
    return array_merge($note, [
        'noteable_type' => 'projects',
        'noteable_id'   => function () {
            return factory(App\Models\v1\Project::class)->create()->id;
        }
    ]);
});

/**
 * Fundraiser Note
 */
$factory->defineAs(App\Models\v1\Note::class, 'fundraiser', function (Faker\Generator $faker) use ($factory) {
    $note = $factory->raw(App\Models\v1\Note::class);
    
    return array_merge($note, [
        'noteable_type' => 'fundraisers',
        'noteable_id'   => function () {
            return factory(App\Models\v1\Fundraiser::class)->create()->id;
        }
    ]);
});

/**
 * Transaction Note
 */
$factory->defineAs(App\Models\v1\Note::class, 'transaction', function (Faker\Generator $faker) use ($factory) {
    $note = $factory->raw(App\Models\v1\Note::class);
    
    return array_merge($note, [
        'noteable_type' => 'transactions',
        'noteable_id'   => function () {
            return factory(App\Models\v1\Transaction::class)->create()->id;
        }
    ]);
});

/**
 * Fund Note
 */
$factory->defineAs(App\Models\v1\Note::class, 'fund', function (Faker\Generator $faker) use ($factory) {
    $note = $factory->raw(App\Models\v1\Note::class);
    
    return array_merge($note, [
        'noteable_type' => 'funds',
        'noteable_id'   => function () {
            return factory(App\Models\v1\Fund::class)->create()->id;
        }
    ]);
});

/**
 * User Note
 */
$factory->defineAs(App\Models\v1\Note::class, 'user', function (Faker\Generator $faker) use ($factory) {
    $note = $factory->raw(App\Models\v1\Note::class);
    
    return array_merge($note, [
        'noteable_type' => 'users',
        'noteable_id'   => function () {
            return factory(App\Models\v1\User::class)->create()->id;
        }
    ]);
});

/**
 * Trip interest Note
 */
$factory->defineAs(App\Models\v1\Note::class, 'trip_interest', function (Faker\Generator $faker) use ($factory) {
    $note = $factory->raw(App\Models\v1\Note::class);
    
    return array_merge($note, [
        'noteable_type' => 'trip_interests',
        'noteable_id'   => function () {
            return factory(App\Models\v1\TripInterest::class)->create()->id;
        }
    ]);
});
