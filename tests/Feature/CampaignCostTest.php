<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\v1\Cost;
use App\Models\v1\Trip;
use App\Models\v1\Price;
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
        
        // get only the campaign's costs
        $response = $this->json('GET', "/api/campaigns/{$campaign->id }/costs");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                [
                    'id', 'name', 'type', 'description'
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
            'type' => 'incremental',
            'description' => 'test'
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure(['message']);
        $this->assertDatabaseHas('costs', [
            'name' => 'General Registration',
            'type' => 'incremental',
            'description' => 'test',
            'cost_assignable_id' => $campaign->id,
            'cost_assignable_type' => 'campaigns'
        ]);
    }

    public function campaign_costs_are_ordered_by_active_at_column()
    {
        // TODO
    }

    public function search_campaign_costs_by_name()
    {
        // TODO
    }

    public function filter_campaign_costs_by_type()
    {
        // TODO
    }

    public function unauthenticated_user_cannot_add_new_costs()
    {
        // TODO
    }

    public function user_without_permission_cannot_add_new_costs()
    {
        // TODO
    }

    /** @test */
    public function validates_request_to_add_new_cost_to_campaign()
    {
        $campaign = factory(Campaign::class)->create();

        $response = $this->json('POST', "/api/campaigns/{$campaign->id }/costs", []);

        $response->assertJsonValidationErrors(['name', 'type', 'description']);
    }

    /** @test */
    public function get_specific_cost_for_campaign()
    {
        // create campaign with cost
        $campaign = factory(Campaign::class)->create();
        $cost = factory(Cost::class)->create([
            'name' => 'General Registration',
            'cost_assignable_id' => $campaign->id, 
            'cost_assignable_type' => 'campaigns',
        ]);

        $response = $this->json('GET', "/api/campaigns/{$campaign->id }/costs/{$cost->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $cost->id,
                'name' => 'General Registration'
            ]
        ]);
    }

    /** @test */
    public function update_a_specific_cost_on_campaign()
    {
        // create campaign with cost
        $campaign = factory(Campaign::class)->create();
        $cost = factory(Cost::class)->create([
            'cost_assignable_id' => $campaign->id, 
            'cost_assignable_type' => 'campaigns',
            'name' => 'General Registration',
            'type' => 'incremental'
        ]);

        $response = $this->json('PUT', "/api/campaigns/{$campaign->id }/costs/{$cost->id}", [
            'name' => 'General Registration Updated',
            'type' => 'static'
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $cost->id,
                'name' => 'General Registration Updated',
                'type' => 'incremental'
            ]
        ]);
    }

    /** @test */
    public function delete_a_cost_from_campaign()
    {
        $campaign = factory(Campaign::class)->create();
        $cost = factory(Cost::class)->create([
            'cost_assignable_id' => $campaign->id, 
            'cost_assignable_type' => 'campaigns',
        ]);

        $response = $this->json('DELETE', "/api/campaigns/{$campaign->id }/costs/{$cost->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('costs', ['id' => $cost->id]);
    }
}
