<?php

/**
 * Generic Pastoral Referral
 */
$factory->define(App\Models\v1\Referral::class, function (Faker\Generator $faker)
{
    return [
        'applicant_name' => $faker->firstName,
        'type' => 'pastoral',
        'attention_to' => $faker->name,
        'recipient_email' => $faker->email,
        'referrer' => [
            'title' => $faker->title,
            'name' => $faker->name,
            'phone' => $faker->phoneNumber,
        ],
        'response' => [
            [
                'q' => 'How Long have you known the applicant?',
                'a' => ''
            ],
            [
                'q' => 'Please list any current roles the applicant serves in at your church:',
                'a' => ''
            ],
            [
                'q' => 'To the best of your knowledge, what is the current state of the applicant\'s spiritual walk?',
                'a' => ''
            ],
            [
                'q' => 'Do you have any reservations about sending this applicant into a foreign nation where spiritual, physical, and social endurance is tested?',
                'a' => ''
            ],
            [
                'q' => 'What are the applicant\'s significant strengths?',
                'a' => ''
            ],
            [
                'q' => 'What are the applicant\'s significant weaknesses?',
                'a' => ''
            ],
            [
                'q' => 'Would you recommend this applicant for a leadership role with Missions.me? If so, why?',
                'a' => ''
            ]
        ]
    ];
});

/**
 * Response to Generic Pastoral Referral
 */
$factory->defineAs(App\Models\v1\Referral::class, 'response', function (Faker\Generator $faker) use ($factory)
{
    $referral = $factory->raw(App\Models\v1\Referral::class);

    return array_merge($referral, [
        'response' => [
            [
                'q' => 'How Long have you known the applicant?',
                'a' => $faker->paragraph(1)
            ],
            [
                'q' => 'Please list any current roles the applicant serves in at your church:',
                'a' => $faker->paragraph(3)
            ],
            [
                'q' => 'To the best of your knowledge, what is the current state of the applicant\'s spiritual walk?',
                'a' => $faker->paragraph(3)
            ],
            [
                'q' => 'Do you have any reservations about sending this applicant into a foreign nation where spiritual, physical, and social endurance is tested?',
                'a' => $faker->paragraph(2)
            ],
            [
                'q' => 'What are the applicant\'s significant strengths?',
                'a' => $faker->paragraph(4)
            ],
            [
                'q' => 'What are the applicant\'s significant weaknesses?',
                'a' => $faker->paragraph(4)
            ],
            [
                'q' => 'Would you recommend this applicant for a leadership role with Missions.me? If so, why?',
                'a' => $faker->paragraph(1)
            ]
        ]
    ]);
});

