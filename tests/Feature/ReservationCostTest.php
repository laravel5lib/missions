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

    /** @test */
    public function add_trip_cost_to_reservation()
    {
        $trip = $this->setupTripWithCosts();
        $reservation = factory(Reservation::class)->create(['trip_id' => $trip->id]);
        
        $response = $this->json('POST', "/api/reservations/{$reservation->id}/costs", [
            'cost_id' => $trip->prices()->first()->id
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('costables', [
            'cost_id' => $trip->prices()->first()->id,
            'costable_id' => $reservation->id,
            'costable_type' => 'reservations'
        ]);
    }

    /** @test */
    public function validates_request_to_add_trip_cost_to_reservation()
    {
        $trip = $this->setupTripWithCosts();
        $reservation = factory(Reservation::class)->create(['trip_id' => $trip->id]);
        
        $response = $this->json('POST', "/api/reservations/{$reservation->id}/costs", []);

        $response->assertJsonValidationErrors(['cost_id']);
    }

    /** @test */
    public function add_custom_cost_to_reservation()
    {
        $reservation = factory(Reservation::class)->create();

        $response = $this->json('POST', "/api/reservations/{$reservation->id}/costs", [
            'name' => 'International Flight',
            'amount' => 2000.00,
            'type' => 'static',
            'description' => 'Round trip flights to and from destination country.'
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('costs', [
            'name' => 'International Flight',
            'amount' => 200000,
            'type' => 'static',
            'description' => 'Round trip flights to and from destination country.',
        ]);
        $this->assertDatabaseHas('costables', [
            'costable_id' => $reservation->id,
            'costable_type' => 'reservations'
        ]);
    }

    /** @test */
    public function validates_request_to_add_custom_cost_to_reservation()
    {
        $reservation = factory(Reservation::class)->create();

        $response = $this->json('POST', "/api/reservations/{$reservation->id}/costs", [
            'type' => 'invalid',
            'description' => 'This is description is way way way too long for a cost description. This should be 120 characters or less but it is a whole lot more than that!!!!',
            'active_at' => 'invalid'
        ]);

        $response->assertJsonValidationErrors(['name', 'amount', 'type', 'description', 'active_at']);
    }

    /** @test */
    public function get_specific_cost_for_reservation()
    {
        $reservation = factory(Reservation::class)->create();
        $reservation->addPrice([
            'name' => 'International Flight',
            'amount' => 2000.00,
            'type' => 'static',
            'description' => 'Round trip flights to and from destination country.',
        ]);

        $response = $this->json('GET', "/api/reservations/{$reservation->id}/costs/{$reservation->costs()->first()->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'data' => [
                        'name' => 'International Flight',
                        'amount' => 2000.00,
                        'type' => 'static',
                        'description' => 'Round trip flights to and from destination country.'
                     ]
                 ]);
    }

    /** @test */
    public function updates_a_reservation_cost()
    {
        $reservation = factory(Reservation::class)->create();
        $reservation->addPrice([
            'name' => 'International Flight',
            'amount' => 2000.00,
            'type' => 'static',
            'description' => 'Round trip flights to and from destination country.',
        ]);

        $response = $this->json('PUT', "/api/reservations/{$reservation->id}/costs/{$reservation->costs()->first()->id}", [
            'name' => 'Updated International Flight',
            'amount' => 2500.00
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'data' => [
                        'name' => 'Updated International Flight',
                        'amount' => 2500.00,
                        'type' => 'static',
                        'description' => 'Round trip flights to and from destination country.'
                     ]
                 ]);
    }

    /** @test */
    public function remove_a_trip_cost_from_reservation()
    {
        $reservation = $this->setupReservationWithCosts();
        $cost = $reservation->prices()->where('cost_assignable_type', 'trips')->first();

        $response = $this->json('DELETE', "/api/reservations/{$reservation->id}/costs/{$cost->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('costables', [
            'cost_id' => $cost->id, 'costable_id' => $reservation->id, 'costable_type' => 'reservations'
        ]);
        $this->assertDatabaseHas('costs', ['id' => $cost->id]);
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
