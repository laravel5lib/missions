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

class CampaignPriceTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function get_all_prices_for_a_campaign()
    {
        // create campaign with price
        $campaign = factory(Campaign::class)->create();
        factory(Price::class)->create(['priceable_id' => $campaign->id, 'priceable_type' => 'campaigns']);
        
        // create trip with price
        $trip = factory(Trip::class)->create(['campaign_id' => $campaign->id]);
        factory(Price::class)->create(['priceable_id' => $trip->id, 'priceable_type' => 'trips']);
        
        // get only the campaign's prices
        $response = $this->json('GET', "/api/campaigns/{$campaign->id }/prices");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                [
                    'uuid', 'amount', 'active_at'
                ]
            ],
            'meta'
        ]);
        $response->assertJson(['meta' => ['total' => 1]]);
    }

    /** @test */
    public function add_new_price_to_campaign()
    {
        $campaign = factory(Campaign::class)->create();
        $cost = factory(Cost::class)->create();

        $response = $this->json('POST', "/api/campaigns/{$campaign->id }/prices", [
            'cost_id' => $cost->id,
            'amount' => 2500.00,
            'active_at' => '01/01/2018'
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure(['message']);
        $this->assertDatabaseHas('prices', [
            'cost_id' => $cost->id,
            'amount' => 250000,
            'active_at' => '2018-01-01 00:00:00',
            'priceable_id' => $campaign->id,
            'priceable_type' => 'campaigns'
        ]);
    }

    public function campaign_prices_are_ordered_by_active_at_column()
    {
        // TODO
    }

    public function search_campaign_prices_by_name()
    {
        // TODO
    }

    public function filter_campaign_prices_by_type()
    {
        // TODO
    }

    public function unauthenticated_user_cannot_add_new_prices()
    {
        // TODO
    }

    public function user_without_permission_cannot_add_new_prices()
    {
        // TODO
    }

    /** @test */
    public function validates_request_to_add_new_price_to_campaign()
    {
        $campaign = factory(Campaign::class)->create();

        $response = $this->json('POST', "/api/campaigns/{$campaign->id }/prices", [
            'active_at' => 'invalid',
            'amount' => 'invalid',
            'cost_id' => 'invalid'
        ]);

        $response->assertJsonValidationErrors(['cost_id', 'amount', 'active_at']);

        $response = $this->json('POST', "/api/campaigns/{$campaign->id }/prices", []);

        $response->assertJsonValidationErrors(['cost_id', 'amount']);
    }

    /** @test */
    public function get_specific_price_for_campaign()
    {
        // create campaign with cost
        $campaign = factory(Campaign::class)->create();
        $price = factory(Price::class)->create([
            'priceable_id' => $campaign->id, 
            'priceable_type' => 'campaigns',
            'amount' => 2500.00,
            'active_at' => '01/01/2018'
        ]);

        $response = $this->json('GET', "/api/campaigns/{$campaign->id }/prices/{$price->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'amount' => 2500.00,
                'active_at' => Carbon::parse('01/01/2018')->toIso8601String()
            ]
        ]);
    }

    /** @test */
    public function update_a_specific_price_on_campaign()
    {
        // create campaign with cost
        $campaign = factory(Campaign::class)->create();
        $price = factory(Price::class)->create([
            'priceable_id' => $campaign->id, 
            'priceable_type' => 'campaigns',
            'amount' => 2500.00,
            'active_at' => '01/01/2018'
        ]);

        $response = $this->json('PUT', "/api/campaigns/{$campaign->id }/prices/{$price->id}", [
            'amount' => 2700.00,
            'active_at' => '01/02/2018'
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'amount' => 2700.00,
                'active_at' => Carbon::parse('01/02/2018')->toIso8601String()
            ]
        ]);
    }

    /** @test */
    public function delete_a_price_from_campaign()
    {
        $campaign = factory(Campaign::class)->create();
        $price = factory(Price::class)->create([
            'priceable_id' => $campaign->id, 
            'priceable_type' => 'campaigns',
        ]);

        $response = $this->json('DELETE', "/api/campaigns/{$campaign->id }/prices/{$price->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('prices', ['id' => $price->id]);
    }
}
