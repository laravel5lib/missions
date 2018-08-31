<?php

return [
    'dashboard' => [
        'reservation' => [
            [
                'label' => 'Overview',
                'url' => 'details'
            ],
            [
                'label' => 'Funding',
                'url' => 'funding'
            ],
            [
                'label' => 'Requirements',
                'url' => 'requirements'
            ],
            [
                'label' => 'Travel',
                'url' => 'travel'
            ],
            [
                'label' => 'Squad',
                'url' => 'squad'
            ],
            [
                'label' => 'Resources',
                'url' => 'resources'
            ],
            [
                'label' => 'Legal',
                'url' => 'legal'
            ]
        ],
        'records' => [
            [
                'label' => 'Passports',
                'url' => 'passports'
            ],
            [
                'label' => 'Visas',
                'url' => 'visas'
            ],
            [
                'label' => 'Medical Releases',
                'url' => 'medical-releases'
            ],
            [
                'label' => 'Media Credentials',
                'url' => 'media-credentials'
            ],
            [
                'label' => 'Medical Credentials',
                'url' => 'medical-credentials'
            ],
            [
                'label' => 'Referrals',
                'url' => 'referrals'
            ],
            [
                'label' => 'Testimonies',
                'url' => 'essays'
            ],
            [
                'label' => 'Influencer Applications',
                'url' => 'influencer-applications'
            ]
        ],
        'project' => [
            [
                'label' => 'Details',
                'url' => 'details'
            ],
            [
                'label' => 'Payments',
                'url' => 'payments'
            ],
            [
                'label' => 'Funds',
                'url' => 'funds'
            ],
        ]
    ],
    'admin' => [
        'toolbar' => [
            [
                'icon' => 'globe',
                'label' => 'Campaigns',
                'url' => 'admin/campaigns',
                'action' => 'view',
                'policy' => 'App\Models\v1\Campaign'
            ],
            [
                'icon' => 'ticket',
                'label' => 'Reservations',
                'url' => 'admin/reservations',
                'action' => 'view',
                'policy' => 'App\Models\v1\Reservation'
            ],
            [
                'icon' => 'users',
                'label' => 'Organizations',
                'url' => 'admin/organizations',
                'action' => 'view',
                'policy' => 'App\Models\v1\Group'
            ],
            [
                'icon' => 'user',
                'label' => 'Users',
                'url' => 'admin/users',
                'action' => 'view',
                'policy' => 'App\Models\v1\User'
            ],
            [
                'icon' => 'user-circle-o',
                'label' => 'Trip Reps',
                'url' => 'admin/representatives',
                'action' => 'view',
                'policy' => 'App\Models\v1\Representative'
            ],
            [
                'icon' => 'usd',
                'label' => 'Transactions',
                'url' => 'admin/transactions',
                'action' => 'view',
                'policy' => 'App\Models\v1\Transaction'
            ],
            [
                'icon' => 'tint',
                'label' => 'Projects',
                'url' => 'admin/causes',
                'action' => 'view',
                'policy' => 'App\Models\v1\Project'
            ],
            [
                'icon' => 'archive',
                'label' => 'Records',
                'url' => 'admin/records',
                'action' => 'view',
                'policy' => 'App\Models\v1\Reservation'
            ],
            [
                'icon' => 'line-chart',
                'label' => 'Reports',
                'url' => 'admin/reports',
                'action' => 'view',
                'policy' => 'App\Models\v1\Report'
            ],
        ],
        'project' => [
            [
                'label' => 'Details',
                'url' => 'details'
            ],
            [
                'label' => 'Costs',
                'url' => 'costs'
            ],
            [
                'label' => 'Payments',
                'url' => 'payments'
            ],
            [
                'label' => 'Funds',
                'url' => 'funds'
            ],
            [
                'label' => 'Important Dates',
                'url' => 'deadlines'
            ],
        ],
        'records' => [
            [
                'label' => 'Passports',
                'url' => 'passports',
                'action' => 'view',
                'policy' => 'App\Models\v1\Passport'
            ],
            [
                'label' => 'Visas',
                'url' => 'visas',
                'action' => 'view',
                'policy' => 'App\Models\v1\Visa'
            ],
            [
                'label' => 'Medical Releases',
                'url' => 'medical-releases',
                'action' => 'view',
                'policy' => 'App\Models\v1\MedicalRelease'
            ],
            [
                'label' => 'Media Credentials',
                'url' => 'media-credentials',
                'action' => 'view',
                'policy' => 'App\Models\v1\Credential'
            ],
            [
                'label' => 'Medical Credentials',
                'url' => 'medical-credentials',
                'action' => 'view',
                'policy' => 'App\Models\v1\Credential'
            ],
            [
                'label' => 'Referrals',
                'url' => 'referrals',
                'action' => 'view',
                'policy' => 'App\Models\v1\Referral'
            ],
            [
                'label' => 'Testimonies',
                'url' => 'essays',
                'action' => 'view',
                'policy' => 'App\Models\v1\Essay'
            ],
            [
                'label' => 'Influencer Applications',
                'url' => 'influencer-applications',
                'action' => 'view',
                'policy' => 'App\Models\v1\Essay'
            ]
        ]
    ]
];
