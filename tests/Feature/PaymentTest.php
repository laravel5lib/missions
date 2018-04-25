<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\v1\Price;
use App\Models\v1\Payment;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function gets_all_payments_for_a_price()
    {
        $price = factory(Price::class)->create();
        factory(Payment::class, 2)->create(['price_id' => $price->id]);
        
        $this->json('get', "/api/prices/{$price->uuid}/payments")
             ->assertStatus(200)
             ->assertJsonStructure([
                 'data' => [
                    ['id', 'percentage_due', 'due_at', 'grace_days']
                 ]
             ]);
    }
}
