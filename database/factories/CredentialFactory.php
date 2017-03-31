<?php

$factory->defineAs(App\Models\v1\Credential::class, 'medical', function(Faker\Generator $faker)
{
    return [
        'holder_id' => $faker->uuid,
        'holder_type' => 'users',
        'type' => 'medical',
        'applicant_name' => $faker->name,
        'content' => $faker->sentences(4),
        'expired_at' => null
    ];
});

$factory->defineAs(App\Models\v1\Credential::class, 'media', function(Faker\Generator $faker)
{
    return [
        'holder_id' => $faker->uuid,
        'holder_type' => 'users',
        'type' => 'media',
        'applicant_name' => $faker->name,
        'content' => $faker->sentences(4),
        'expired_at' => null
    ];
});
