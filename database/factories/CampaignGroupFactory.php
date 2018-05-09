<?php

use Faker\Generator as Faker;
use App\Models\v1\CampaignGroup;

$factory->define(CampaignGroup::class, function (Faker $faker) {
    return [
        'group_id'        => function () {
            return factory(App\Models\v1\Group::class)->create()->id;
        },
        'campaign_id'     => function () {
            return factory(App\Models\v1\Campaign::class)->create()->id;
        },
        'status_id' => $faker->numberBetween(1, 4),
        'meta' => [
            ['label' => 'Label', 'value' => 'Value']
        ]
    ];
});
