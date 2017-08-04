<?php

/**
 * Generic Initiative
 */
$factory->define(App\Models\v1\ProjectInitiative::class, function (Faker\Generator $faker) {
    return [
        'project_cause_id' => function () {
            return factory(App\Models\v1\ProjectCause::class, 'orphans')->create()->id;
        },
        'type' => $faker->catchPhrase,
        'short_desc' => $faker->text(200),
        'country_code' => function (array $initiative) use ($faker) {
            $countries = App\Models\v1\ProjectCause::find($initiative['project_cause_id'])->countries;
            return $faker->randomElement($countries)['code'];
        },
        'upload_id' => function () {
            return factory(App\Models\v1\Upload::class, 'avatar')->create()->id;
        },
        'started_at' => \Carbon\Carbon::now()->subYear(),
        'ended_at' => \Carbon\Carbon::now()->addYear()
    ];
});
