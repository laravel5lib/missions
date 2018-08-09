<?php

namespace Tests\Feature\Api\Payments;

use Tests\TestCase;
use App\Models\v1\Cost;
use App\Models\v1\Trip;
use App\Models\v1\Price;
use App\Models\v1\Campaign;
use App\Models\v1\Reservation;
use App\Models\v1\CampaignGroup;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddPaymentTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function create_campaign_group_price_with_payments()
    {
        $group = factory(CampaignGroup::class)->create();
        $cost = factory(Cost::class)->create([
            'type' => 'incremental', 
            'cost_assignable_id' => $group->campaign_id, 
            'cost_assignable_type' => 'campaigns'
        ]);
        
        $this->json('post', "/api/campaign-groups/{$group->uuid}/prices", [
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
    public function create_trip_price_with_payments()
    {
        $trip = factory(Trip::class)->create();
        $cost = factory(Cost::class)->create([
            'type' => 'incremental', 
            'cost_assignable_id' => $trip->campaign_id, 
            'cost_assignable_type' => 'campaigns'
        ]);
        
        $this->json('post', "/api/trips/{$trip->id}/prices", [
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
    public function create_reservation_price_with_payments()
    {
        $reservation = factory(Reservation::class)->create();
        $cost = factory(Cost::class)->create([
            'type' => 'incremental', 
            'cost_assignable_id' => $reservation->trip->campaign_id,
            'cost_assignable_type' => 'campaigns'
        ]);
        
        $this->json('post', "/api/reservations/{$reservation->id}/prices", [
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
    public function updates_payments_for_campaign_group_price()
    {
        $group = factory(CampaignGroup::class)->create();
        $cost = factory(Cost::class)->create([
            'type' => 'incremental',
            'cost_assignable_id' => $group->campaign_id,
            'cost_assignable_type' => 'campaigns'
        ]);
        $price = factory(Price::class)->create([
            'model_id' => $group->uuid, 
            'model_type' => 'campaign-groups', 
            'cost_id' => $cost->id, 
            'active_at' => now()->subDay()
        ]);
        
        $this->json('put', "/api/campaign-groups/{$group->uuid}/prices/{$price->uuid}", [
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
        $group = factory(CampaignGroup::class)->create();
        $cost = factory(Cost::class)->create([
            'type' => 'incremental', 
            'cost_assignable_id' => $group->campaign_id,
            'cost_assignable_type' => 'campaigns'
        ]);

        $this->json('post', "/api/campaign-groups/{$group->uuid}/prices", [
            'amount' => 1200.00,
            'cost_id' => $cost->id,
            'active_at' => '01/01/2018'
        ])->assertJsonValidationErrors(['payments']);
    }

    /** @test */
    public function price_with_incremental_cost_must_have_at_least_one_payment()
    {
        $group = factory(CampaignGroup::class)->create();
        $cost = factory(Cost::class)->create([
            'type' => 'incremental', 
            'cost_assignable_id' => $group->campaign_id,
            'cost_assignable_type' => 'campaigns'
        ]);
        $price = factory(Price::class)->create([
            'model_id' => $group->uuid, 'model_type' => 'campaign-groups', 'cost_id' => $cost->id
        ]);
        
        $this->json('put', "/api/campaign-groups/{$group->uuid}/prices/{$price->uuid}", ['payments' => null])
             ->assertJsonValidationErrors(['payments']);

        $this->json('put', "/api/campaign-groups/{$group->uuid}/prices/{$price->uuid}", ['payments' => []])
             ->assertJsonValidationErrors(['payments']);
    }

    /** @test */
    public function validates_that_one_payment_covers_the_full_amount()
    {
        $group = factory(CampaignGroup::class)->create();
        $cost = factory(Cost::class)->create([
            'type' => 'incremental', 
            'cost_assignable_id' => $group->campaign_id,
            'cost_assignable_type' => 'campaigns'
        ]);
        $price = factory(Price::class)->create([
            'model_id' => $group->uuid, 'model_type' => 'campaign-groups', 'cost_id' => $cost->id
        ]);
        
        $this->json('put', "/api/campaign-groups/{$group->uuid}/prices/{$price->uuid}", [
            'payments' => [
                [
                    'percentage_due' => 50,
                    'due_at' => '01/01/2019',
                    'grace_days' => 3
                ]
            ]
        ])->assertJsonValidationErrors(['payments']);

        $this->json('put', "/api/campaign-groups/{$group->uuid}/prices/{$price->uuid}", [
            'payments' => [
                [
                    'percentage_due' => 100,
                    'due_at' => '01/01/2019',
                    'grace_days' => 3
                ],
                [
                    'percentage_due' => 100,
                    'due_at' => '01/07/2019',
                    'grace_days' => 3
                ]
            ]
        ])->assertJsonValidationErrors(['payments']);
    }

    /** @test */
    public function validates_payment_dates()
    {
        $group = factory(CampaignGroup::class)->create();
        $cost = factory(Cost::class)->create([
            'type' => 'incremental', 
            'cost_assignable_id' => $group->campaign_id, 
            'cost_assignable_type' => 'campaigns'
        ]);
        $price = factory(Price::class)->create([
            'model_id' => $group->uuid, 'model_type' => 'campaign-groups', 'cost_id' => $cost->id
        ]);
        
        $this->json('put', "/api/campaign-groups/{$group->uuid}/prices/{$price->uuid}", [
            'payments' => [
                [
                    'percentage_due' => 50,
                    'due_at' => '01/01/2019',
                    'grace_days' => 3
                ],
                [
                    'percentage_due' => 100,
                    'due_at' => '01/01/2018',
                    'grace_days' => 3
                ]
            ]
        ])->assertJsonValidationErrors(['payments']);
    }
}
