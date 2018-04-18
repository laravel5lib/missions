<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\v1\Reservation;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReservationCostTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function get_all_costs_for_a_reservation()
    {
        $reservation = factory(Reservation::class)->create();

        $response = $this->json('GET', "/api/reservations/{$reservation->id}/costs");

        $response->assertStatus(200);
    }
}
