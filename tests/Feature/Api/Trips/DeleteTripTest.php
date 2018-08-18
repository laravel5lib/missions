<?php

namespace Tests\Feature\Api\Trips;

use Tests\TestCase;
use App\Models\v1\Trip;
use App\Models\v1\User;
use App\Models\v1\Reservation;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteTripTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function deletes_trip_and_drops_its_reservations()
    {
        Passport::actingAs(factory(User::class, 'admin')->create());

        $trip = factory(Trip::class)->create();
        $reservation = factory(Reservation::class)->create(['trip_id' => $trip->id]);
        $otherReservation = factory(Reservation::class)->create();

        $this->json('DELETE', "/api/trips/$trip->id")
             ->assertStatus(204);
        
        $this->assertDatabaseHas('trips', ['id' => $trip->id]);
        $this->assertDatabaseHas('reservations', ['id' => $reservation->id]);
        $this->assertNotNull($trip->fresh()->deleted_at);
        $this->assertNotNull($reservation->fresh()->deleted_at);
        $this->assertNull($otherReservation->fresh()->deleted_at);
    }
}
