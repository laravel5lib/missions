<?php

namespace Tests\Feature\Api\Credentials;

use Tests\TestCase;
use App\Models\v1\Reservation;
use App\Models\v1\MedicalCredential;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteMedicalCredentialTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function delete_medical_credential()
    {
        $credential = factory(MedicalCredential::class)->create();

        $response = $this->deleteJson("/api/medical-credentials/{$credential->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('credentials', ['id' => $credential->id]);
    }

     /** @test */
    public function detach_all_reservations_when_medical_credential_is_deleted()
    {
        $credential = factory(MedicalCredential::class)->create();

        $reservation = factory(Reservation::class)->create();

        $credential->attachToReservation($reservation->id);

        $response = $this->deleteJson("/api/medical-credentials/{$credential->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing(
            'reservation_documents', 
            [
                'reservation_id' => $reservation->id,
                'documentable_id' => $credential->id,
                'documentable_type' => 'medical_credentials'
            ]
        );
    }
}
