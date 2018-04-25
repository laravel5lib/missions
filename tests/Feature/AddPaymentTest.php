<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\v1\Cost;
use App\Models\v1\Price;
use App\Models\v1\Campaign;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddPaymentTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function create_campaign_price_with_payments()
    {
        $campaign = factory(Campaign::class)->create();
        $cost = factory(Cost::class)->create(['type' => 'incremental']);
        
        $this->json('post', "/api/campaigns/{$campaign->id}/prices", [
            'amount' => 1200.00,
            'cost_id' => $cost->id,
            'active_at' => '01/01/2018',
            'payments' => [
                [
                    'percentage_due' => 50,
                    'due_at' => '01/01/2019',
                    'grace_days' => 3
                ],
                [
                    'percentage_due' => 100,
                    'due_at' => '07/01/2019',
                    'grace_days' => 3
                ]
            ]
        ])->assertStatus(201);

        $this->assertDatabaseHas('price_payments', [
            'percentage_due' => 50,
            'due_at' => '2019-01-01 23:59:59', // end of day
            'grace_days' => 3
        ]);

        $this->assertDatabaseHas('price_payments', [
            'percentage_due' => 100,
            'due_at' => '2019-07-01 23:59:59', // end of day
            'grace_days' => 3
        ]);
    }

    /** @test */
    public function updates_payments_for_campaign_price()
    {
        $campaign = factory(Campaign::class)->create();
        $cost = factory(Cost::class)->create(['type' => 'incremental']);
        $price = factory(Price::class)->create([
            'model_id' => $campaign->id, 'model_type' => 'campaigns', 'cost_id' => $cost->id
        ]);
        
        $this->json('put', "/api/campaigns/{$campaign->id}/prices/{$price->uuid}", [
            'payments' => [
                [
                    'percentage_due' => 50,
                    'due_at' => '01/01/2019',
                    'grace_days' => 3
                ],
                [
                    'percentage_due' => 100,
                    'due_at' => '07/01/2019',
                    'grace_days' => 3
                ]
            ]
        ])->assertStatus(200);

        $this->assertDatabaseHas('price_payments', [
            'price_id' => $price->id,
            'percentage_due' => 50,
            'due_at' => '2019-01-01 23:59:59', // end of day
            'grace_days' => 3
        ]);

        $this->assertDatabaseHas('price_payments', [
            'price_id' => $price->id,
            'percentage_due' => 100,
            'due_at' => '2019-07-01 23:59:59', // end of day
            'grace_days' => 3
        ]);
    }

    /** @test */
    public function payments_are_required_to_create_price_if_cost_is_incremental()
    {
        $campaign = factory(Campaign::class)->create();
        $cost = factory(Cost::class)->create(['type' => 'incremental']);

        $this->json('post', "/api/campaigns/{$campaign->id}/prices", [
            'amount' => 1200.00,
            'cost_id' => $cost->id,
            'active_at' => '01/01/2018'
        ])->assertJsonValidationErrors(['payments']);
    }

    /** @test */
    public function price_with_incremental_cost_must_have_at_least_one_payment()
    {
        $campaign = factory(Campaign::class)->create();
        $cost = factory(Cost::class)->create(['type' => 'incremental']);
        $price = factory(Price::class)->create([
            'model_id' => $campaign->id, 'model_type' => 'campaigns', 'cost_id' => $cost->id
        ]);
        
        $this->json('put', "/api/campaigns/{$campaign->id}/prices/{$price->uuid}", ['payments' => null])
             ->assertJsonValidationErrors(['payments']);

        $this->json('put', "/api/campaigns/{$campaign->id}/prices/{$price->uuid}", ['payments' => []])
             ->assertJsonValidationErrors(['payments']);
    }
}