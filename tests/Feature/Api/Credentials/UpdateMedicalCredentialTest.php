<?php

namespace Tests\Feature\Api\Credentials;

use Tests\TestCase;
use App\Models\v1\MedicalCredential;
use App\Models\v1\Reservation;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateMedicalCredentialTest extends TestCase
{
    /** @test */
    public function update_medical_credential_and_save_changes_in_storage()
    {
        $medical = factory(MedicalCredential::class)->create();

        $response = $this->putJson("/api/medical-credentials/{$medical->id}", [
            'applicant_name' => 'John Doe'
        ]);

        $response->assertOk()
                 ->assertJson([
                    'data' => [
                        'applicant_name' => 'John Doe'
                    ]
                 ]);

        $this->assertDatabaseHas(
            'credentials', 
            ['id' => $medical->id, 'type' => 'medical', 'applicant_name' => 'John Doe']
        );
    }

    /** @test */
    public function attach_medical_credential_to_reservation_when_updated()
    {
        $reservation = factory(Reservation::class)->create();

        $credential = factory(MedicalCredential::class)->create();

        $response = $this->putJson("/api/medical-credentials/{$credential->id}", [
            'reservation_id' => $reservation->id
        ]);

        $response->assertOk();

        $this->assertDatabaseHas(
            'reservation_documents', 
            ['reservation_id' => $reservation->id, 'documentable_type' => 'medical_credentials']
        );
    }
}
