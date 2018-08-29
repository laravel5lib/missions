<?php

use App\Models\v1\User;
use App\Models\v1\Referral;

/**
 * Generic Pastoral Referral
 */
$factory->define(Referral::class, function (Faker\Generator $faker) {
    return [
        'user_id' => function () {
            return factory(User::class)->create()->id;
        },
        'applicant_name' => $faker->firstName. ' '. $faker->lastName,
        'type' => 'pastoral',
        'attention_to' => $faker->name,
        'recipient_email' => $faker->email,
        'sent_at' => $faker->dateTimeThisYear(),
        'referrer' => [
            'title' => $faker->title,
            'name' => $faker->name,
            'phone' => $faker->phoneNumber,
        ],
        'response' => [

            [
                'q' => 'How long have you known the applicant?',
                'a' => '',
                'type' => 'textarea'
            ],
            [
                'q' => 'Does the applicant currently attend your church?',
                'a' => '',
                'type' => 'radio'
            ],
            [
                'q' => 'Do you have any concerns about this applicant’s ability to honor authority or adhere to instruction?  Please explain.',
                'a' => '',
                'type' => 'textarea'
            ],
            [
                'q' => 'Do you have any concerns about this applicant\'s spiritual, physical, and social endurance in a foreign nation for 7-14 days? Please explain.',
                'a' => '',
                'type' => 'textarea'
            ],
            [
                'q' => 'Do you recommend this applicant for missions service with Missions.Me?',
                'a' => '',
                'type' => 'radio'
            ]
        ]
    ];
});

/**
 * Response to Generic Pastoral Referral
 */
$factory->defineAs(Referral::class, 'response', function (Faker\Generator $faker) use ($factory) {
    $referral = $factory->raw(Referral::class);

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
