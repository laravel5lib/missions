<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\v1\Cost;
use App\Models\v1\Price;
use App\Models\v1\Payment;
use App\Models\v1\Campaign;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function gets_campaign_price_with_payments()
    {
        $campaign = factory(Campaign::class)->create();
        $price = factory(Price::class)->create(['model_id' => $campaign->id, 'model_type' => 'campaigns']);
        factory(Payment::class, 2)->create(['price_id' => $price->id]);
        
        $this->json('get', "/api/campaigns/{$campaign->id}/prices/{$price->uuid}")
             ->assertStatus(200)
             ->assertJsonStructure([
                 'data' => [
                    'payments' => [
                        ['id', 'percentage_due', 'due_at', 'grace_days']
                    ]
                 ]
             ]);
    }

    /** @test */
    public function adds_payments_to_campaign_price()
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
}
