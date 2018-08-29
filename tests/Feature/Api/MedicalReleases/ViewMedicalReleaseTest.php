<?php

namespace Tests\Feature\Api\MedicalReleases;

use Tests\TestCase;
use App\Models\v1\Trip;
use App\Models\v1\User;
use App\Models\v1\Group;
use App\Models\v1\Reservation;
use App\Models\v1\MedicalAllergy;
use App\Models\v1\MedicalRelease;
use App\Models\v1\MedicalCondition;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewMedicalReleaseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function view_all_medical_releases()
    {
        factory(MedicalRelease::class, 2)->create();

        $response = $this->getJson('/api/medical-releases');

        $response->assertOk()
                 ->assertJsonStructure([
                    'data' => [
                        [
                            'id',
                            'user_id',
                            'name',
                            'pregnant',
                            'height',
                            'weight',
                            'ins_provider',
                            'ins_policy_no',
                            'is_risk',
                            'takes_medication',
                            'has_conditions',
                            'has_allergies',
                            'emergency_contact',
                            'created_at',
                            'updated_at'
                        ]
                    ]
                ]);
    }

    /** @test */
    public function view_medical_release_by_id()
    {
        $release = factory(MedicalRelease::class)->create([
            'name' => 'John Doe',
            'ins_provider' => 'Acme Medical',
            'ins_policy_no' => 'ABC123'
        ]);

        $response = $this->getJson("/api/medical-releases/{$release->id}");

        $response->assertOk()
                 ->assertJsonStructure([
                    'data' => [
                        'user',
                        'conditions',
                        'allergies'
                    ]
                 ])
                 ->assertJson([
                    'data' => [
                        'id' => $release->id,
                        'name' => 'John Doe',
                        'ins_provider' => 'Acme Medical',
                        'ins_policy_no' => 'ABC123'
                    ]
                 ]);
    }

    /** @test */
    public function include_conditions_with_medical_release()
    {
        $release = factory(MedicalRelease::class)->create();
        $release->conditions()->saveMany([
            factory(MedicalCondition::class)->make(['name' => 'Diabetes']),
            factory(MedicalCondition::class)->make(['name' => 'Asthma'])
        ]);

        $response = $this->getJson('/api/medical-releases?include=conditions');

        $response->assertOk()
                 ->assertJson([
                    'data' => [
                        [
                            'conditions' => [
                                ['name' => 'Asthma'],
                                ['name' => 'Diabetes']
                            ]
                        ]
                    ]
                 ]);
    }

    /** @test */
    public function include_allergies_with_medical_release()
    {
        $release = factory(MedicalRelease::class)->create();
        $release->conditions()->saveMany([
            factory(MedicalAllergy::class)->make(['name' => 'Peanuts']),
            factory(MedicalAllergy::class)->make(['name' => 'Wheat'])
        ]);

        $response = $this->getJson('/api/medical-releases?include=allergies');

        $response->assertOk()
                 ->assertJson([
                    'data' => [
                        [
                            'allergies' => [
                                ['name' => 'Peanuts'],
                                ['name' => 'Wheat']
                            ]
                        ]
                    ]
                 ]);
    }

    /** @test */
    public function include_user_with_medical_release()
    {
        $user = factory(User::class)->create(['first_name' => 'John', 'last_name' => 'Doe']);
        $release = factory(MedicalRelease::class)->create(['user_id' => $user->id]);

        $response = $this->getJson('/api/medical-releases?include=user');

        $response->assertOk()
                 ->assertJson([
                    'data' => [
                        [
                            'user' => [
                                'first_name' => 'John',
                                'last_name' => 'Doe'
                            ]
                        ]
                    ]
                 ]);
    }

    /** @test */
    public function filter_medical_releases_by_name()
    {
        factory(MedicalRelease::class)->create(['name' => 'John Doe']);
        factory(MedicalRelease::class)->create(['name' => 'Jane Doe']);

        $response = $this->getJson('/api/medical-releases?filter[name]=john');

        $response->assertOk()
                 ->assertJson([
                    'data' => [
                        ['name' => 'John Doe']
                    ]
                 ])
                 ->assertJsonMissing([
                    'data' => [
                        ['name' => 'Jane Doe']
                    ]
                 ]);
    }

    /** @test */
    public function filter_medical_releases_by_ins_provider()
    {
        factory(MedicalRelease::class)->create(['ins_provider' => 'Acme Medical']);
        factory(MedicalRelease::class)->create(['ins_provider' => 'Blue Cross']);

        $response = $this->getJson('/api/medical-releases?filter[ins_provider]=blue');

        $response->assertOk()
                 ->assertJson([
                    'data' => [
                        ['ins_provider' => 'Blue Cross']
                    ]
                 ])
                 ->assertJsonMissing([
                    'data' => [
                        ['ins_provider' => 'Acme Medical']
                    ]
                 ]);
    }

    /** @test */
    public function filter_medical_releases_by_ins_policy_no()
    {
        factory(MedicalRelease::class)->create(['ins_policy_no' => 'ABC123']);
        factory(MedicalRelease::class)->create(['ins_policy_no' => 'DEF456']);

        $response = $this->getJson('/api/medical-releases?filter[ins_policy_no]=123');

        $response->assertOk()
                 ->assertJson([
                    'data' => [
                        ['ins_policy_no' => 'ABC123']
                    ]
                 ])
                 ->assertJsonMissing([
                    'data' => [
                        ['ins_policy_no' => 'DEF456']
                    ]
                 ]);
    }

    /** @test */
    public function filter_medical_releases_by_user_id()
    {
        $user = factory(User::class)->create();
        factory(MedicalRelease::class)->create(['user_id' => $user->id]);
        factory(MedicalRelease::class)->create();

        $response = $this->getJson("/api/medical-releases?filter[user_id]={$user->id}");

        $response->assertOk()
                 ->assertJson([
                    'data' => [
                        ['user_id' => $user->id]
                    ],
                     'meta' => ['total' => 1]
                 ]);
    }

    /** @test */
    public function get_medical_releases_only_for_users_with_reservations_managed_by_team_coordinator()
    {
        $coordinator = factory(User::class)->create();
        $group = factory(Group::class)->create();
        $group->managers()->attach($coordinator->id);
        $trip = factory(Trip::class)->create(['group_id' => $group->id]);
        $reservation = factory(Reservation::class)->create(['trip_id' => $trip->id]);
        $release = factory(MedicalRelease::class)->create(['user_id' => $reservation->user_id, 'name' => 'John Doe']);
        factory(MedicalRelease::class)->create(['name' => 'Jane Doe']);

        $response = $this->getJson("/api/medical-releases?filter[managed_by]={$coordinator->id}");

        $response->assertOk()
                 ->assertJson([
                    'data' => [
                        [
                            'name' => 'John Doe',
                            'user_id' => $reservation->user_id
                        ]
                    ]
                 ])
                 ->assertJsonMissing([
                    'data' => [
                        ['name' => 'Jane Doe']
                    ]
                 ]);
    }
}
