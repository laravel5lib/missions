<?php

namespace Tests\Feature\Api\Essays;

use Tests\TestCase;
use App\Models\v1\Essay;
use App\Models\v1\Reservation;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteEssayTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function delete_essay_and_remove_from_storage()
    {
        $essay = factory(Essay::class)->create();

        $this->deleteJson("/api/essays/{$essay->id}")
             ->assertStatus(204);

        $this->assertDatabaseMissing('essays', ['id' => $essay->id]);
    }

    /** @test */
    public function detach_all_reservations_when_medical_release_is_deleted()
    {
        $essay = factory(Essay::class)->create();

        $reservation = factory(Reservation::class)->create();

        $essay->attachToReservation($reservation->id);

        $response = $this->deleteJson("/api/essays/{$essay->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing(
            'reservation_documents', 
            [
                'reservation_id' => $reservation->id,
                'documentable_id' => $essay->id,
                'documentable_type' => 'essays'
            ]
        );
    }
}
