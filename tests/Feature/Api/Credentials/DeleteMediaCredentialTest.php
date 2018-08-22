<?php

namespace Tests\Feature\Api\Credentials;

use Tests\TestCase;
use App\Models\v1\Reservation;
use App\Models\v1\MediaCredential;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteMediaCredentialTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function delete_media_credential()
    {
        $credential = factory(MediaCredential::class)->create();

        $response = $this->deleteJson("/api/media-credentials/{$credential->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('credentials', ['id' => $credential->id]);
    }

     /** @test */
    public function detach_all_reservations_when_media_credential_is_deleted()
    {
        $credential = factory(MediaCredential::class)->create();

        $reservation = factory(Reservation::class)->create();

        $credential->attachToReservation($reservation->id);

        $response = $this->deleteJson("/api/media-credentials/{$credential->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing(
            'reservation_documents', 
            [
                'reservation_id' => $reservation->id,
                'documentable_id' => $credential->id,
                'documentable_type' => 'media_credentials'
            ]
        );
    }
}
