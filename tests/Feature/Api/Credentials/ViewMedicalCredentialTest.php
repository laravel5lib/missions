<?php

namespace Tests\Feature\Api\Credentials;

use Tests\TestCase;
use App\Models\v1\Trip;
use App\Models\v1\User;
use App\Models\v1\Group;
use App\Models\v1\Reservation;
use App\Models\v1\MediaCredential;
use App\Models\v1\MedicalCredential;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewMedicalCredentialTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function get_list_of_medical_credentials()
    {
        factory(MedicalCredential::class)->create();
        factory(MediaCredential::class)->create();

        $response = $this->getJson('/api/medical-credentials');

        $response->assertOk()
                 ->assertJsonStructure([
                    'data' => [
                        [
                            'id', 'type', 'user_id', 'applicant_name', 
                            'content', 'expired_at', 'created_at', 'updated_at'
                        ]
                    ]
                 ])
                 ->assertJson([
                    'data' => [
                        ['type' => 'medical']
                    ],
                    'meta' => [
                        'total' => 1
                    ]
                 ])
                 ->assertJsonMissing([
                    'data' => [
                        ['type' => 'media']
                    ]
                 ]);
    }

    /** @test */
    public function get_medical_credential_by_id()
    {
        $credential = factory(MedicalCredential::class)->create();

        $response = $this->getJson("/api/medical-credentials/{$credential->id}");

        $response->assertOk()
                 ->assertJson([
                    'data' => ['id' => $credential->id]
                 ]);
    }

    /** @test */
    public function filter_list_of_medical_credentials_by_applicant_name()
    {
        factory(MedicalCredential::class)->create(['applicant_name' => 'John Doe']);
        factory(MedicalCredential::class)->create(['applicant_name' => 'James Smith']);

        $response = $this->getJson('/api/medical-credentials?filter[applicant_name]=john');

        $response->assertOk()
                 ->assertJson([
                    'data' => [
                        ['applicant_name' => 'John Doe']
                    ]
                 ])
                 ->assertJsonMissing([
                    'data' => [
                        ['applicant_name' => 'James Smith']
                    ]
                 ]);
    }

    /** @test */
    public function filter_list_of_medical_credentials_by_user_id()
    {
        $johnsCredentials = factory(MedicalCredential::class)->create(['applicant_name' => 'John Doe']);
        $jamesCredentials = factory(MedicalCredential::class)->create(['applicant_name' => 'James Smith']);

        $response = $this->getJson("/api/medical-credentials?filter[user_id]={$johnsCredentials->user_id}");

        $response->assertOk()
                 ->assertJson([
                    'data' => [
                        ['user_id' => $johnsCredentials->user_id]
                    ]
                 ])
                 ->assertJsonMissing([
                    'data' => [
                        ['user_id' => $jamesCredentials->user_id]
                    ]
                 ]);
    }

    /** @test */
    public function get_medical_credentials_only_for_users_with_reservations_managed_by_team_coordinator()
    {
        $coordinator = factory(User::class)->create();
        $group = factory(Group::class)->create();
        $group->managers()->attach($coordinator->id);
        $trip = factory(Trip::class)->create(['group_id' => $group->id]);
        $reservation = factory(Reservation::class)->create(['trip_id' => $trip->id]);
        $credential = factory(MedicalCredential::class)->create(['user_id' => $reservation->user_id, 'applicant_name' => 'John Doe']);
        factory(MedicalCredential::class)->create(['applicant_name' => 'Jane Doe']);

        $response = $this->getJson("/api/medical-credentials?filter[managed_by]={$coordinator->id}");

        $response->assertOk()
                 ->assertJson([
                    'data' => [
                        [
                            'applicant_name' => 'John Doe',
                            'user_id' => $reservation->user_id
                        ]
                    ]
                 ])
                 ->assertJsonMissing([
                    'data' => [
                        ['applicant_name' => 'Jane Doe']
                    ]
                 ]);
    }
}
