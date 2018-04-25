<?php

namespace Tests\Feature;

use Tests\TestCase;
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
}
