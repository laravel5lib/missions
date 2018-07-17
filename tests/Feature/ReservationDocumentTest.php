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

    /** @test */
    public function remove_passport_from_reservation()
    {
        $reservation = factory(Reservation::class)->create();
        $passport = factory(Passport::class)->create(['user_id' => $reservation->user_id]);
        $reservation->passports()->attach($passport->id);

        $response = $this->deleteJson("/api/reservations/{$reservation->id}/passports/{$passport->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing(
            'reservation_documents',
            [
                'reservation_id' => $reservation->id,
                'documentable_id' => $passport->id, 
                'documentable_type' => 'passports'
            ]
        );
    }

    /** @test */
    public function get_passports_for_reservation()
    {
        $reservation = factory(Reservation::class)->create();
        $passport = factory(Passport::class)->create(['user_id' => $reservation->user_id]);
        $reservation->passports()->attach($passport->id);

        $response = $this->getJson("/api/reservations/{$reservation->id}/passports");

        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'given_names',
                    'surname',
                    'number',
                    'birth_country',
                    'birth_country_name',
                    'citizenship',
                    'citizenship_name',
                    'upload_id',
                    'expires_at',
                    'created_at',
                    'updated_at',
                    'expired'
                ]
            ]
        ]);
    }
}
