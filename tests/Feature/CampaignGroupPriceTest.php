<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\v1\Cost;
use App\Models\v1\Trip;
use App\Models\v1\Price;
use App\Models\v1\Payment;
use App\Models\v1\Campaign;
use App\Models\v1\CampaignGroup;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CampaignGroupPriceTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function get_all_prices_for_a_campaign_group()
    {
        // create campaign with price
        $group = factory(CampaignGroup::class)->create();
        factory(Price::class)->create(['model_id' => $group->uuid, 'model_type' => 'campaign-groups']);
        
        // get only the campaign's prices
        $response = $this->json('GET', "/api/campaign-groups/{$group->uuid}/prices");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                [
                    'id', 'amount', 'active_at'
                ]
            ],
            'meta'
        ]);
        $response->assertJson(['meta' => ['total' => 1]]);
    }

    /** @test */
    public function add_new_price_to_campaign_group()
    {
        $group = factory(CampaignGroup::class)->create();
        $cost = factory(Cost::class)->create();

        $response = $this->json('POST', "/api/campaign-groups/{$group->uuid}/prices", [
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
            'model_id' => $group->uuid,
            'model_type' => 'campaign-groups'
        ]);
    }

    public function campaign_group_prices_are_ordered_by_active_at_column()
    {
        // TODO
    }

    public function search_campaign_group_prices_by_name()
    {
        // TODO
    }

    public function filter_campaign_group_prices_by_type()
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
    public function validates_request_to_add_new_price_to_campaign_group()
    {
        $group = factory(CampaignGroup::class)->create();

        $response = $this->json('POST', "/api/campaign-groups/{$group->uuid}/prices", [
            'active_at' => 'invalid',
            'amount' => 'invalid',
            'cost_id' => 'invalid'
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['cost_id', 'amount', 'active_at']);

        $response = $this->json('POST', "/api/campaign-groups/{$group->uuid}/prices", []);

        $response->assertStatus(422)->assertJsonValidationErrors(['cost_id', 'amount']);
    }

    /** @test */
    public function get_specific_price_for_campaign_group()
    {
        // create campaign with cost
        $group = factory(CampaignGroup::class)->create();
        $price = factory(Price::class)->create([
            'model_id' => $group->uuid, 
            'model_type' => 'campaign-groups',
            'amount' => 2500.00,
            'active_at' => '01/01/2018'
        ]);

        $response = $this->json('GET', "/api/campaign-groups/{$group->uuid }/prices/{$price->uuid}");

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'amount' => 2500.00,
                'active_at' => Carbon::parse('01/01/2018')->toIso8601String()
            ]
        ]);
    }

    /** @test */
    public function update_a_specific_price_on_campaign_group()
    {
        // create campaign with cost
        $group = factory(CampaignGroup::class)->create();
        $price = factory(Price::class)->create([
            'model_id' => $group->uuid, 
            'model_type' => 'campaign-groups',
            'amount' => 2500.00,
            'active_at' => '01/01/2018'
        ]);

        $response = $this->json('PUT', "/api/campaign-groups/{$group->uuid}/prices/{$price->uuid}", [
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
    public function delete_a_price_with_payments_from_campaign_group()
    {
        $group = factory(CampaignGroup::class)->create();
        $price = factory(Price::class)->create([
            'model_id' => $group->uuid, 
            'model_type' => 'campaign-groups',
        ]);
        factory(Payment::class, 2)->create(['price_id' => $price->id]);

        $response = $this->json('DELETE', "/api/campaign-groups/{$group->uuid}/prices/{$price->uuid}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('prices', ['id' => $price->id]);
        $this->assertDatabaseMissing('price_payments', ['price_id' => $price->id]);
    }
}
