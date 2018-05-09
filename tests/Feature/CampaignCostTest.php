<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\v1\Cost;
use App\Models\v1\Trip;
use App\Models\v1\User;
use App\Models\v1\Price;
use App\Models\v1\Payment;
use App\Models\v1\Campaign;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CampaignCostTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function get_all_costs_for_a_campaign()
    {
        $this->setupAdminUser();

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
    public function campaign_costs_are_ordered_by_name_column()
    {
        $this->setupAdminUser();

        $campaign = factory(Campaign::class)->create();

        factory(Cost::class)->create([
            'name' => 'General Registration',
            'type' => 'incremental',
            'cost_assignable_id' => $campaign->id, 
            'cost_assignable_type' => 'campaigns',
        ]);

        factory(Cost::class)->create([
            'name' => 'Early Registration',
            'type' => 'incremental',
            'cost_assignable_id' => $campaign->id, 
            'cost_assignable_type' => 'campaigns',
        ]);

        factory(Cost::class)->create([
            'name' => 'Deposit',
            'type' => 'upfront',
            'cost_assignable_id' => $campaign->id, 
            'cost_assignable_type' => 'campaigns',
        ]);

        $response = $this->json('GET', "/api/campaigns/{$campaign->id }/costs");

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                ['name' => 'Deposit'],
                ['name' => 'Early Registration'],
                ['name' => 'General Registration'],
            ]
        ]);
    }

    /** @test */
    public function add_new_cost_to_campaign()
    {
        $this->setupAdminUser();

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

    /** @test */
    public function user_without_permission_cannot_add_new_costs()
    {
        Passport::actingAs(factory(User::class)->create());

        $campaign = factory(Campaign::class)->create();

        $response = $this->json('POST', "/api/campaigns/{$campaign->id }/costs", [
            'name' => 'General Registration',
            'type' => 'incremental',
            'description' => 'test'
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function validates_request_to_add_new_cost_to_campaign()
    {
        $this->setupAdminUser();

        $campaign = factory(Campaign::class)->create();

        $response = $this->json('POST', "/api/campaigns/{$campaign->id }/costs", []);

        $response->assertJsonValidationErrors(['name', 'type', 'description']);
    }

    /** @test */
    public function cost_name_must_be_unique()
    {
        $this->setupAdminUser();

        $campaign = factory(Campaign::class)->create();
        $cost = factory(Cost::class)->create([
            'name' => 'Deposit',
            'cost_assignable_id' => $campaign->id, 
            'cost_assignable_type' => 'campaigns',
        ]);

        $this->json('POST', "/api/campaigns/{$campaign->id }/costs", [
            'name' => 'Deposit',
            'type' => 'incremental',
            'description' => 'test'
        ])->assertJsonValidationErrors(['name']);

        $this->json('PUT', "/api/campaigns/{$campaign->id }/costs/{$cost->id}", [
            'name' => 'Deposit'
        ])->assertStatus(200);
    }

    /** @test */
    public function get_specific_cost_for_campaign()
    {
        $this->setupAdminUser();

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
        $this->setupAdminUser();

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
        $this->setupAdminUser();
        
        $campaign = factory(Campaign::class)->create();
        $cost = factory(Cost::class)->create([
            'cost_assignable_id' => $campaign->id, 
            'cost_assignable_type' => 'campaigns',
        ]);
        $price = factory(Price::class)->create(['cost_id' => $cost->id]);
        factory(Payment::class, 2)->create(['price_id' => $price->id]);
        $trip = factory(Trip::class)->create(['campaign_id' => $campaign->id]);
        $trip->addPrice(['price_id' => $price->uuid]);

        $this->json('DELETE', "/api/campaigns/{$campaign->id }/costs/{$cost->id}")
             ->assertStatus(204);
        $this->assertDatabaseMissing('costs', ['id' => $cost->id]);
        $this->assertDatabaseMissing('prices', ['cost_id' => $cost->id]);
        $this->assertDatabaseMissing('price_payments', ['price_id' => $price->id]);
        $this->assertDatabaseMissing('priceables', ['price_id' => $price->id]);
    }

    private function setupAdminUser()
    {
        Passport::actingAs(factory(User::class, 'admin')->create());
    }
}
