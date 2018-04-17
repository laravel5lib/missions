<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\v1\Cost;
use App\Models\v1\Trip;
use App\Models\v1\Campaign;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CampaignCostTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function get_all_costs_for_a_campaign()
    {
        // create campaign with cost
        $campaign = factory(Campaign::class)->create();
        factory(Cost::class)->create(['cost_assignable_id' => $campaign->id, 'cost_assignable_type' => 'campaigns']);
        
        // create trip with cost
        $trip = factory(Trip::class)->create(['campaign_id' => $campaign->id]);
        factory(Cost::class)->create(['cost_assignable_id' => $trip->id, 'cost_assignable_type' => 'trips']);
        
        // get only the campaign's costs
        $response = $this->json('GET', "/api/campaigns/{$campaign->id }/costs");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                [
                    'id', 'name', 'amount', 'description', 'type', 'active_at'
                ]
            ],
            'meta'
        ]);
        $response->assertJson(['meta' => ['total' => 1]]);
    }

    /** @test */
    public function add_new_cost_to_campaign()
    {
        $campaign = factory(Campaign::class)->create();

        $response = $this->json('POST', "/api/campaigns/{$campaign->id }/costs", [
            'name' => 'General Registration',
            'amount' => 2500.00,
            'type' => 'incremental',
            'description' => 'Base cost for general registration.',
            'active_at' => '01/01/2018'
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure(['message']);
        $this->assertDatabaseHas('costs', [
            'name' => 'General Registration',
            'amount' => 250000,
            'type' => 'incremental',
            'description' => 'Base cost for general registration.',
            'cost_assignable_id' => $campaign->id,
            'cost_assignable_type' => 'campaigns'
        ]);
    }

    /** @test */
    public function validates_request_to_add_new_cost_to_campaign()
    {
        $campaign = factory(Campaign::class)->create();

        $response = $this->json('POST', "/api/campaigns/{$campaign->id }/costs", [
            'type' => 'invalid',
            'description' => 'This is description is way way way too long for a cost description. This should be 120 characters or less but it is a whole lot more than that!!!!',
            'active_at' => 'invalid'
        ]);

        $response->assertJsonValidationErrors(['name', 'amount', 'type', 'description', 'active_at']);
    }

    /** @test */
    public function get_specific_cost_for_campaign()
    {
        // create campaign with cost
        $campaign = factory(Campaign::class)->create();
        $cost = factory(Cost::class)->create([
            'cost_assignable_id' => $campaign->id, 
            'cost_assignable_type' => 'campaigns',
            'name' => 'General Registration',
            'amount' => 2500.00,
            'description' => 'Base registration cost.',
            'type' => 'incremental',
            'active_at' => '01/01/2018'
        ]);

        $response = $this->json('GET', "/api/campaigns/{$campaign->id }/costs/{$cost->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $cost->id,
                'name' => 'General Registration',
                'amount' => 2500.00,
                'description' => 'Base registration cost.',
                'type' => 'incremental',
                'active_at' => Carbon::parse('01/01/2018')->toIso8601String()
            ]
        ]);
    }
}
