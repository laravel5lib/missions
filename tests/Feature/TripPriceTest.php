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

class TripPriceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function get_all_trip_prices()
    {
        $trip = $this->setupTripWithPrices();

        $response = $this->json('GET', "/api/trips/{$trip->id}/prices");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                [
                    'id', 'amount', 'active_at'
                ]
            ],
            'meta'
        ]);
        $response->assertJson([
            'meta' => ['total' => 3]
        ]);
    }

    /** @test */
    public function order_trip_prices_by_active_date()
    {
        $trip = factory(Trip::class)->create();
        $firstPrice = factory(Price::class)->create([
            'active_at' => '04/01/2018',
            'model_id' => $trip->id, 
            'model_type' => 'trips'
        ]);
        $secondPrice = factory(Price::class)->create([
            'active_at' => '01/01/2018',
            'model_id' => $trip->id, 
            'model_type' => 'trips'
        ]);
        $trip->priceables()->attach([$secondPrice->id, $firstPrice->id]);

        $response = $this->json('GET', "/api/trips/{$trip->id}/prices");

        $response->assertJson([
            'data' => [
                ['active_at' => Carbon::parse('01/01/2018')->toIso8601String()],
                ['active_at' => Carbon::parse('04/01/2018')->toIso8601String()]
            ]
        ]);
    }

    /** @test */
    public function search_trip_prices_by_name()
    {
        $trip = factory(Trip::class)->create();
        $generalCost = factory(Cost::class)->create(['name' => 'General Reg.']);
        $generalPrice = factory(Price::class)->create([
            'cost_id' => $generalCost->id, 
            'model_id' => $trip->id, 
            'model_type' => 'trips'
        ]);
        $earlyCost = factory(Cost::class)->create(['name' => 'Early Reg.']);
        $earlyPrice = factory(Price::class)->create([
            'cost_id' => $earlyCost->id, 
            'model_id' => $trip->id, 
            'model_type' => 'trips'
        ]);
        $trip->priceables()->attach([$generalPrice->id, $earlyPrice->id]);

        $response = $this->json('GET', "/api/trips/{$trip->id}/prices", ['search' => 'Early Reg.']);

        $response->assertJson([
            'data' => [
                ['cost' => ['name' => 'Early Reg.']],
            ],
            'meta' => ['total' => 1]
        ]);
    }

    /** @test */
    public function filter_trip_prices_by_type()
    {
        $trip = factory(Trip::class)->create();
        $incremental = factory(Cost::class)->create(['type' => 'incremental']);
        $incrementalPrice = factory(Price::class)->create([
            'cost_id' => $incremental->id,
            'model_id' => $trip->id, 
            'model_type' => 'trips'
        ]);
        $static = factory(Cost::class)->create(['type' => 'static']);
        $staticPrice = factory(Price::class)->create([
            'cost_id' => $static->id,
            'model_id' => $trip->id, 
            'model_type' => 'trips'
        ]);
        $trip->priceables()->attach([$incrementalPrice->id, $staticPrice->id]);

        $response = $this->json('GET', "/api/trips/{$trip->id}/prices", ['type' => 'incremental']);

        $response->assertJson([
            'data' => [
                ['cost' => ['type' => 'incremental']],
            ],
            'meta' => ['total' => 1]
        ]);
    }

    /** @test */
    public function add_campaign_price_to_a_trip()
    {
        $campaign = $this->setupCampaignWithPrices();
        $trip = factory(Trip::class)->create(['campaign_id' => $campaign->id]);
        $prices = $campaign->prices->toArray();

        $response = $this->json('POST', "/api/trips/{$trip->id}/prices", [
            'price_id' => $prices[0]['uuid']
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure(['message']);
        $this->assertDatabaseHas('priceables', [
            'price_id' => $prices[0]['id'],
            'priceable_id' => $trip->id,
            'priceable_type' => 'trips'
        ]);
    }

    /** @test */
    public function validates_request_to_add_a_campaign_price_to_a_trip()
    {
        $trip = factory(Trip::class)->create();

        $response = $this->json('POST', "/api/trips/{$trip->id}/prices", []);

        $response->assertJsonValidationErrors(['price_id']);
    }

    /** @test */
    public function validates_that_campaign_price_is_unique()
    {
        $trip = $this->setupTripWithPrices();
        $price = $trip->prices()->first();

        $this->json('POST', "/api/trips/{$trip->id}/prices", [
            'price_id' => $price->uuid
        ])->assertJsonValidationErrors(['price_id']);
    }

    /** @test */
    public function add_custom_price_to_a_trip()
    {
        $trip = factory(Trip::class)->create();
        $cost = factory(Cost::class)->create();

        $response = $this->json('POST', "/api/trips/{$trip->id}/prices", [
            'cost_id' => $cost->id,
            'amount' => 1500.00,
            'active_at' => '01/01/2018'
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure(['message']);
        $this->assertDatabaseHas('prices', [
            'cost_id' => $cost->id,
            'amount' => 150000,
            'active_at' => '2018-01-01 00:00:00'
        ]);
        $this->assertDatabaseHas('priceables', [
            'priceable_id' => $trip->id,
            'priceable_type' => 'trips'
        ]);
    }

    /** @test */
    public function validates_request_to_add_custom_price_to_trip()
    {
        $trip = factory(Trip::class)->create();

        $response = $this->json('POST', "/api/trips/{$trip->id}/prices", [
            'cost_id' => 'invalid',
            'amount' => 'invalid',
            'active_at' => 'invalid'
        ]);

        $response->assertJsonValidationErrors(['cost_id', 'amount', 'active_at']);

        $response = $this->json('POST', "/api/trips/{$trip->id}/prices", []);

        $response->assertJsonValidationErrors(['cost_id', 'amount']);
    }

    /** @test */
    public function validates_that_cost_is_unique_when_adding_a_custom_price_to_trip()
    {
        $trip = factory(Trip::class)->create();
        $cost = factory(Cost::class)->create();
        $price = factory(Price::class)->create([
            'cost_id' => $cost->id,
            'model_id' => $trip->id, 
            'model_type' => 'trips'
        ]);

        $this->json('POST', "/api/trips/{$trip->id}/prices", [
            'cost_id' => $cost->id,
            'amount' => 1500.00,
            'active_at' => '01/01/2018'
        ])->assertJsonValidationErrors(['cost_id']);
    }

    /** @test */
    public function get_specific_trip_price()
    {
        $trip = $this->setupTripWithPrices();
        $price = $trip->prices()->first();

        $response = $this->json('GET', "/api/trips/{$trip->id}/prices/{$price->uuid}");

        $response->assertStatus(200);
        $response->assertJson(['data' => ['id' => $price->uuid]]);
    }

    /** @test */
    public function update_a_custom_trip_price()
    {
        $trip = $this->setupTripWithPrices();
        $price = $trip->prices()->first();
        $this->assertNotEquals(2000.00, $price->amount);

        $response = $this->json('PUT', "/api/trips/{$trip->id}/prices/{$price->uuid}", [
            'amount' => 2000.00
        ]);

        $response->assertStatus(200);
        $response->assertJson(['data' => ['amount' => 2000.00]]);
    }

    /** @test */
    public function validates_a_request_to_update_custom_trip_price()
    {
        $trip = $this->setupTripWithPrices();
        $price = $trip->prices()->first();

        $response = $this->json('PUT', "/api/trips/{$trip->id}/prices/{$price->uuid}", [
            'amount' => 'invalid',
            'active_at' => 'invalid'
        ]);

        $response->assertJsonValidationErrors(['amount', 'active_at']);
    }

    public function throws_exception_when_updating_cost_not_owned_by_trip()
    {
        //
    }

    /** @test */
    public function remove_a_campaign_price()
    {
        $trip = $this->setupTripWithPrices();
        $price = $trip->priceables()->where('model_type', 'campaigns')->first();

        $response = $this->json('DELETE', "/api/trips/{$trip->id}/prices/{$price->uuid}");

        $response->assertStatus(204);
        $this->assertDatabaseHas('prices', ['id' => $price->id]);
        $this->assertDatabaseMissing('priceables', [
            'price_id' => $price->id, 'priceable_id' => $trip->id, 'priceable_type' => 'trips'
        ]);
    }

    /** @test */
    public function remove_a_custom_trip_price()
    {
        $trip = $this->setupTripWithPrices();
        $price = $trip->prices()->first();

        $response = $this->json('DELETE', "/api/trips/{$trip->id}/prices/{$price->uuid}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('prices', ['id' => $price->id]);
        $this->assertDatabaseMissing('priceables', [
            'price_id' => $price->id, 'priceable_id' => $trip->id, 'priceable_type' => 'trips'
        ]);
    }

    private function setupTripWithPrices()
    {
        $campaign = $this->setupCampaignWithPrices();

        $trip = factory(Trip::class)->create(['campaign_id' => $campaign->id]);
        $price = factory(Price::class)->create([
            'model_id' => $trip->id, 
            'model_type' => 'trips'
        ]);
        
        $campaignPrices = $campaign->prices->pluck('id')->toArray();
        $trip->priceables()->attach([
            $price->id, $campaignPrices[0], $campaignPrices[1]
        ]);

        return $trip;
    }

    private function setupCampaignWithPrices()
    {
        $campaign = factory(Campaign::class)->create();
        factory(Price::class, 2)->create([
            'model_id' => $campaign->id, 
            'model_type' => 'campaigns'
        ]);

        return $campaign;
    }
}
