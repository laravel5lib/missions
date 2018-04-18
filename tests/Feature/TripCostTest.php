<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\v1\Cost;
use App\Models\v1\Trip;
use App\Models\v1\Campaign;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TripCostTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function get_all_trip_costs()
    {
        $trip = $this->setupTripWithCosts();

        $response = $this->json('GET', "/api/trips/{$trip->id}/costs");

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
            'meta' => ['total' => 3]
        ]);
    }

    public function order_trip_costs_by_active_date()
    {
        // TODO
    }

    public function search_trip_costs_by_name()
    {
        // TODO
    }

    public function filter_trip_costs_by_type()
    {
        // TODO
    }

    /** @test */
    public function add_campaign_cost_to_a_trip()
    {
        $campaign = $this->setupCampaignWithCosts();
        $trip = factory(Trip::class)->create(['campaign_id' => $campaign->id]);
        $costs = $campaign->costs->pluck('id')->toArray();

        $response = $this->json('POST', "/api/trips/{$trip->id}/costs", [
            'cost_id' => $costs[0]
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure(['message']);
        $this->assertDatabaseHas('costables', [
            'cost_id' => $costs[0],
            'costable_id' => $trip->id,
            'costable_type' => 'trips'
        ]);
    }

    /** @test */
    public function validates_request_to_add_a_campaign_cost_to_a_trip()
    {
        $trip = factory(Trip::class)->create();

        $response = $this->json('POST', "/api/trips/{$trip->id}/costs", []);

        $response->assertJsonValidationErrors(['cost_id']);
    }

    /** @test */
    public function add_custom_cost_to_a_trip()
    {
        $trip = factory(Trip::class)->create();

        $response = $this->json('POST', "/api/trips/{$trip->id}/costs", [
            'name' => 'Custom Cost',
            'amount' => 1500.00,
            'type' => 'static',
            'description' => 'A custom cost added to the trip.',
            'active_at' => '01/01/2018'
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure(['message']);
        $this->assertDatabaseHas('costs', [
            'name' => 'Custom Cost',
            'amount' => 150000,
            'type' => 'static',
            'description' => 'A custom cost added to the trip.',
        ]);
        $this->assertDatabaseHas('costables', [
            'costable_id' => $trip->id,
            'costable_type' => 'trips'
        ]);
    }

    /** @test */
    public function validates_request_to_add_custom_cost_to_trip()
    {
        $trip = factory(Trip::class)->create();

        $response = $this->json('POST', "/api/trips/{$trip->id}/costs", [
            'type' => 'invalid',
            'description' => 'This is description is way way way too long for a cost description. This should be 120 characters or less but it is a whole lot more than that!!!!',
            'active_at' => 'invalid'
        ]);

        $response->assertJsonValidationErrors(['name', 'amount', 'type', 'description', 'active_at']);
    }

    /** @test */
    public function get_specific_trip_cost()
    {
        $trip = $this->setupTripWithCosts();
        $cost = $trip->costs()->first();

        $response = $this->json('GET', "/api/trips/{$trip->id}/costs/{$cost->id}");

        $response->assertStatus(200);
        $response->assertJson(['data' => ['id' => $cost->id]]);
    }

    /** @test */
    public function update_a_custom_trip_cost()
    {
        $trip = $this->setupTripWithCosts();
        $cost = $trip->costs()->first();
        $this->assertNotEquals('Updated Trip Cost.', $cost->name);

        $response = $this->json('PUT', "/api/trips/{$trip->id}/costs/{$cost->id}");

        $response->assertStatus(200);
        $response->assertJson(['data' => ['name' => 'Updated Trip Cost']]);
    }

    public function validates_a_request_to_update_custom_trip_cost()
    {
        // TODO
    }

    /** @test */
    public function remove_a_custom_trip_cost()
    {
        $trip = $this->setupTripWithCosts();
        $cost = $trip->costs()->first();

        $response = $this->json('DELETE', "/api/trips/{$trip->id}/costs/{$cost->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('costs', ['id' => $cost->id]);
        $this->assertDatabaseMissing('costables', [
            'cost_id' => $cost->id, 'costable_id' => $trip->id, 'costable_type' => 'trips'
        ]);
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
        $campaign = factory(Campaign::class)->create();
        factory(Cost::class, 2)->create([
            'cost_assignable_id' => $campaign->id, 
            'cost_assignable_type' => 'campaigns'
        ]);

        return $campaign;
    }
}
