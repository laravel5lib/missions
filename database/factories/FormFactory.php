<?php

use App\Models\v1\FormSubmission;
use App\Models\v1\User;

$factory->defineAs(FormSubmission::class, 'testimony', function (Faker\Generator $faker) {
    return [
        'type' => 'testimonies',
        'content' => [
            [
                'id' => 'field_01',
                'label' => 'Decision to Follow Christ',
                'description' => 'Please describe how you decided to follow Christ.',
                'input' => [
                    'type' => 'textarea',
                    'placeholder' => 'Please explain...',
                    'options' => null,
                    'value' => 'Marianne or husbands if at stronger ye. Considered is as middletons uncommonly. Promotion perfectly ye consisted so. His chatty dining for effect ladies active. Equally journey wishing not several behaved chapter she two sir. Deficient procuring favourite extensive you two. Yet diminution she impossible understood age.',
                ],
                'helptext' => null,
                'rules' => ['required']
            ],
            [
                'id' => 'field_02',
                'label' => 'Church Involvement',
                'description' => 'Please describe your church involvement.',
                'input' => [
                    'type' => 'textarea',
                    'placeholder' => 'Please explain...',
                    'options' => null,
                    'value' => 'Is at purse tried jokes china ready decay an. Small its shy way had woody downs power. To denoting admitted speaking learning my exercise so in. Procured shutters mr it feelings. To or three offer house begin taken am at. As dissuade cheerful overcame so of friendly he indulged unpacked. Alteration connection to so as collecting me. Difficult in delivered extensive at direction allowance. Alteration put use diminution can considered sentiments interested discretion. An seeing feebly stairs am branch income me unable.',
                ],
                'helptext' => null,
                'rules' => ['required']
            ],
        ],
        'user_id' => function () {
            return factory(User::class)->create()->id;
        }
    ];
});