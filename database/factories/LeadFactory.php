<?php

$factory->define(App\Models\v1\Lead::class, function (Faker\Generator $faker) {
    return [
        'category_id' => $faker->numberBetween(2, 5),
        'content' => ['key' => 'value']
    ];
});

$factory->state(App\Models\v1\Lead::class, 'organization', function ($faker) {
    return [
        'category_id' => 1,
        'content' => [
            'campaign_of_interest' => $faker->randomElement(['1Nation1Day', 'Advance', 'Christmas in India']),
            'spoke_with_rep' => $faker->boolean,
            'organization' => $faker->company,
            'type' => $faker->randomElement(['church', 'business', 'nonprofit', 'school', 'independent', 'other', 'youth']),
            'address_one' => $faker->streetAddress,
            'address_two' => $faker->secondaryAddress,
            'city' => $faker->city,
            'state' => $faker->state,
            'zip' => $faker->postCode,
            'country' => $faker->countryCode,
            'contact' => $faker->name,
            'position' => $faker->jobTitle,
            'phone_one' => stripPhone($faker->e164PhoneNumber),
            'phone_two' => stripPhone($faker->e164PhoneNumber),
            'email' => $faker->email
        ]
    ];
});