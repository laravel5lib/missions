<?php

return [
    'conditions' => [
        'operators' => [
            'limit_to', 
            'except_for', 
            'equal_to', 
            'not_equal_to', 
            'greater_than', 
            'less_than', 
            'greater_than_or_equal_to', 
            'less_than_or_equal_to'
        ],
        'types' => [
            'role',
            'age',
            'gender',
            'status',
            'country'
        ]
    ],
    'document_types' => [
        'passports',
        'visas',
        'essays',
        'referrals',
        'medical_releases',
        'minor_releases',
        'media_credentials',
        'medical_credentials',
        'airport_preferenes',
        'arrival_designations',
        'influencer_applications'
    ]
];