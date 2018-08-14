<?php

namespace Tests\Feature\Api\Visas;

use Tests\TestCase;
use App\Models\v1\Visa;
use App\Models\v1\Reservation;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteVisaTest extends TestCase
{
    /** @test */
    public function delete_visa_and_remove_from_storage()
    {
        $visa = factory(Visa::class)->create();

        $response = $this->deleteJson("/api/visas/{$visa->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('visas', ['id' => $visa->id]);
    }

    /** @test */
    public function detach_all_reservations_when_visa_is_deleted()
    {
        $visa = factory(Visa::class)->create();

        $reservation = factory(Reservation::class)->create();

        $visa->attachToReservation($reservation->id);

        $response = $this->deleteJson("/api/visas/{$visa->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing(
            'reservation_documents', 
            [
                'reservation_id' => $reservation->id,
                'documentable_id' => $visa->id,
                'documentable_type' => 'visas'
            ]
        );
    }
}
