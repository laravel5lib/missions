<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\v1\Passport;
use App\Models\v1\Reservation;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReservationDocumentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function add_passport_to_reservation()
    {
        $reservation = factory(Reservation::class)->create();
        $passport = factory(Passport::class)->create(['user_id' => $reservation->user_id]);

        $response = $this->postJson("/api/reservations/{$reservation->id}/passports", [
            'document_id' => $passport->id
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas(
            'reservation_documents', 
            [
                'reservation_id' => $reservation->id,
                'documentable_id' => $passport->id, 
                'documentable_type' => 'passports'
            ]
        );
    }
}
