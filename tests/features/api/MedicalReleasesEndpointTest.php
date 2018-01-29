<?php

use App\Models\v1\User;
use App\Models\v1\MedicalRelease;

class MedicalReleasesEndpointTest extends BrowserKitTestCase
{
    use AuthenticatedUserSetup;

    /**
     * @test
     */
    public function returns_medical_releases()
    {
        $essays = factory(MedicalRelease::class, 2)->create([
                'user_id' => function () {
                    return factory(User::class)->create()->id;
                }
            ]);

        $this->get('/api/medical/releases')
            ->assertResponseOk()
            ->seeJsonStructure([
                'data' => [
                    '*' => [
                        'id', 'user_id', 'name', 'ins_provider', 'ins_policy_no',
                        'is_risk', 'takes_medication', 'has_conditions', 'has_allergies',
                        'emergency_contact', 'created_at', 'updated_at'
                    ]
                ]
            ]);
    }

    /**
     * @test
     */
    public function creates_medical_release_with_conditions_and_allergies()
    {
        $release = factory(MedicalRelease::class)->make([
            'name' => 'joe',
            'user_id' => function () {
                return factory(User::class)->create()->id;
            },
            'conditions' => [
                [
                    'name'       => 'fake condition',
                    'medication' => true,
                    'diagnosed'  => true,
                ],
                [
                    'name'       => 'fake condition two',
                    'medication' => true,
                    'diagnosed'  => true,
                ]
            ],
            'allergies' => [
                [
                    'name'       => 'fake allergy',
                    'medication' => true,
                    'diagnosed'  => true,
                ],
                [
                    'name'       => 'fake allergy two',
                    'medication' => true,
                    'diagnosed'  => true,
                ]
            ]
        ])->toArray();

        $this->post('/api/medical/releases', $release)
            ->assertResponseOk()
            ->seeJson([
                'name' => 'joe',
                'has_conditions' => true,
                'has_allergies' => true
            ]);
    }

    /**
     * @test
     */
    public function updates_medical_release_with_conditions_and_allergies()
    {
        $release = factory(MedicalRelease::class)->create([
            'name' => 'joe',
            'user_id' => function () {
                return factory(User::class)->create()->id;
            }
        ]);

        $release['name'] = 'john';
        $release['conditions'] = [
            [
                'name'       => 'fake condition updated',
                'medication' => true,
                'diagnosed'  => true,
            ]
        ];
        $release['allergies'] = [
            [
                'name'       => 'fake allergy',
                'medication' => true,
                'diagnosed'  => true,
            ]
        ];

        $this->put('/api/medical/releases/'.$release->id, $release->toArray())
            ->assertResponseOk()
            ->seeJson([
                'name' => 'john',
                'has_conditions' => true,
                'has_allergies' => true
            ]);
    }
}
