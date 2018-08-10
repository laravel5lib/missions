<?php

namespace Tests\Feature\Api\Passports;

use Tests\TestCase;
use App\Models\v1\Passport;
use App\Models\v1\Reservation;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeletePassportTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function delete_passport()
    {
        $passport = factory(Passport::class)->create();

        $response = $this->deleteJson("/api/passports/{$passport->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('passports', ['id' => $passport->id]);
    }

    /** @test */
    public function detach_all_reservations_when_passport_is_deleted()
    {
        $passport = factory(Passport::class)->create();

        $reservation = factory(Reservation::class)->create();

        $passport->attachToReservation($reservation->id);

        $response = $this->deleteJson("/api/passports/{$passport->id}");

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
}
