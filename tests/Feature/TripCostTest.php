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

    private function setupTripWithCosts()
    {
        $campaign = $this->setupCampaignWithCosts();

        $trip = factory(Trip::class)->create(['campaign_id' => $campaign->id]);
        factory(Cost::class)->create([
            'cost_assignable_id' => $trip->id, 
            'cost_assignable_type' => 'trips'
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
