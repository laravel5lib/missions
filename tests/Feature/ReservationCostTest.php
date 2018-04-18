<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\v1\Cost;
use App\Models\v1\Trip;
use App\Models\v1\Campaign;
use App\Models\v1\Reservation;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReservationCostTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function get_all_costs_for_a_reservation()
    {
        $reservation = $this->setupReservationWithCosts();

        $response = $this->json('GET', "/api/reservations/{$reservation->id}/costs");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                [
                    'id', 'name', 'amount', 'description', 'type', 'active_at'
                ]
            ],
            'meta'
        ]);
        $response->assertJson([
            'meta' => ['total' => 4]
        ]);
    }

    private function setupReservationWithCosts()
    {
        $trip = $this->setupTripWithCosts();

        $reservation = factory(Reservation::class)->create(['trip_id' => $trip->id]);
        $cost = factory(Cost::class)->create([
            'cost_assignable_id' => $reservation->id, 
            'cost_assignable_type' => 'reservations'
        ]);

        $tripCosts = $trip->prices->pluck('id')->toArray();
        $reservation->prices()->attach([
            $cost->id, $tripCosts[0], $tripCosts[1], $tripCosts[2]
        ]);

        return $reservation;
    }

    private function setupTripWithCosts()
    {
        $campaign = $this->setupCampaignWithCosts();

        $trip = factory(Trip::class)->create(['campaign_id' => $campaign->id]);
        $cost = factory(Cost::class)->create([
            'cost_assignable_id' => $trip->id, 
            'cost_assignable_type' => 'trips'
        ]);
        
        $campaignCosts = $campaign->costs->pluck('id')->toArray();
        $trip->prices()->attach([
            $cost->id, $campaignCosts[0], $campaignCosts[1]
        ]);

        return $trip;
    }

    private function setupCampaignWithCosts()
    {
        $this->setupArbitraryCosts();

        $campaign = factory(Campaign::class)->create();
        factory(Cost::class, 2)->create([
            'cost_assignable_id' => $campaign->id, 
            'cost_assignable_type' => 'campaigns'
        ]);

        return $campaign;
    }

    private function setupArbitraryCosts()
    {
        factory(Cost::class, 2)->create();
    }
}
