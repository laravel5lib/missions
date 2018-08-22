<?php

namespace Tests\Feature\Api\Credentials;

use Tests\TestCase;
use App\Models\v1\MediaCredential;
use App\Models\v1\Reservation;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateMediaCredentialTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function update_media_credential_and_save_changes_in_storage()
    {
        $media = factory(MediaCredential::class)->create();

        $response = $this->putJson("/api/media-credentials/{$media->id}", [
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
            ['id' => $media->id, 'type' => 'media', 'applicant_name' => 'John Doe']
        );
    }

    /** @test */
    public function attach_media_credential_to_reservation_when_updated()
    {
        $reservation = factory(Reservation::class)->create();

        $credential = factory(MediaCredential::class)->create();

        $response = $this->putJson("/api/media-credentials/{$credential->id}", [
            'reservation_id' => $reservation->id
        ]);

        $response->assertOk();

        $this->assertDatabaseHas(
            'reservation_documents', 
            ['reservation_id' => $reservation->id, 'documentable_type' => 'media_credentials']
        );
    }

}
