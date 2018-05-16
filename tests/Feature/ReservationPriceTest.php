<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\v1\Cost;
use App\Models\v1\Trip;
use App\Models\v1\Price;
use App\Models\v1\Campaign;
use App\Models\v1\Reservation;
use App\Models\v1\CampaignGroup;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReservationPriceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function get_all_prices_for_a_reservation()
    {
        $reservation = $this->setupReservationWithPrices();

        $response = $this->json('GET', "/api/reservations/{$reservation->id}/prices");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                [
                    'uuid', 'amount', 'active_at', 'cost'
                ]
            ],
            'meta'
        ]);
        $response->assertJson([
            'meta' => ['total' => 4]
        ]);
    }

    /** @test */
    public function add_trip_price_to_reservation()
    {
        $trip = $this->setupTripWithPrices();
        $reservation = factory(Reservation::class)->create(['trip_id' => $trip->id]);
        
        $response = $this->json('POST', "/api/reservations/{$reservation->id}/prices", [
            'price_id' => $trip->priceables()->first()->uuid
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('priceables', [
            'price_id' => $trip->priceables()->first()->id,
            'priceable_id' => $reservation->id,
            'priceable_type' => 'reservations'
        ]);
    }

    /** @test */
    public function validates_request_to_add_trip_price_to_reservation()
    {
        $trip = $this->setupTripWithPrices();
        $reservation = factory(Reservation::class)->create(['trip_id' => $trip->id]);
        
        $response = $this->json('POST', "/api/reservations/{$reservation->id}/prices", []);

        $response->assertJsonValidationErrors(['price_id']);
    }

    /** @test */
    public function validates_that_price_is_unique()
    {
        $reservation = $this->setupReservationWithPrices();
        $price = $reservation->prices()->first();

        $this->json('POST', "/api/reservations/{$reservation->id}/prices", [
            'price_id' => $price->uuid
        ])->assertJsonValidationErrors(['price_id']);
    }

    /** @test */
    public function add_custom_price_to_reservation()
    {
        $reservation = factory(Reservation::class)->create();
        $cost = factory(Cost::class)->create();

        $response = $this->json('POST', "/api/reservations/{$reservation->id}/prices", [
            'cost_id' => $cost->id,
            'amount' => 2000.00
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('prices', [
            'cost_id' => $cost->id,
            'amount' => 200000
        ]);
        $this->assertDatabaseHas('priceables', [
            'priceable_id' => $reservation->id,
            'priceable_type' => 'reservations'
        ]);
    }

    /** @test */
    public function validates_request_to_add_custom_price_to_reservation()
    {
        $reservation = factory(Reservation::class)->create();

        $response = $this->json('POST', "/api/reservations/{$reservation->id}/prices", [
            'cost_id' => 'invalid',
            'amount' => 'invalid',
            'active_at' => 'invalid'
        ]);

        $response->assertJsonValidationErrors(['cost_id', 'amount', 'active_at']);

        $response = $this->json('POST', "/api/reservations/{$reservation->id}/prices", []);

        $response->assertJsonValidationErrors(['cost_id', 'amount']);
    }

    /** @test */
    public function validates_that_cost_is_unique_when_adding_a_custom_price_to_reservation()
    {
        $reservation = factory(Reservation::class)->create();
        $cost = factory(Cost::class)->create();
        $price = factory(Price::class)->create([
            'cost_id' => $cost->id,
            'model_id' => $reservation->id, 
            'model_type' => 'reservations'
        ]);

        $this->json('POST', "/api/reservations/{$reservation->id}/prices", [
            'cost_id' => $cost->id,
            'amount' => 1500.00,
            'active_at' => '01/01/2018'
        ])->assertJsonValidationErrors(['cost_id']);
    }

    /** @test */
    public function get_specific_price_for_reservation()
    {
        $reservation = factory(Reservation::class)->create();
        $cost = factory(Cost::class)->create();
        $reservation->addPrice([
            'cost_id' => $cost->id,
            'amount' => 2000.00
        ]);

        $response = $this->json('GET', "/api/reservations/{$reservation->id}/prices/{$reservation->prices()->first()->uuid}");

        $response->assertStatus(200)
                 ->assertJson([
                     'data' => [
                        'amount' => 2000.00
                     ]
                 ]);
    }

    /** @test */
    public function updates_a_reservation_price()
    {
        $reservation = factory(Reservation::class)->create();
        $cost = factory(Cost::class)->create();
        $reservation->addPrice([
            'cost_id' => $cost->id,
            'amount' => 2000.00
        ]);

        $response = $this->json('PUT', "/api/reservations/{$reservation->id}/prices/{$reservation->prices()->first()->uuid}", [
            'amount' => 2500.00
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'data' => [
                        'amount' => 2500.00
                     ]
                 ]);
    }

    /** @test */
    public function locks_reservation_current_rate()
    {
        $reservation = $this->setupReservationWithPrices();

        $this->json('POST', "/api/reservations/{$reservation->id}/prices/lock")
             ->assertStatus(200);

        $this->assertEquals(1, $reservation->getCurrentRate()->pivot->locked);
    }

    /** @test */
    public function unlocks_reservation_current_rate()
    {
        $reservation = $this->setupReservationWithPrices();

        $this->json('DELETE', "/api/reservations/{$reservation->id}/prices/lock")
             ->assertStatus(200);

        $this->assertEquals(0, $reservation->getCurrentRate()->pivot->locked);
    }

    /** @test */
    public function remove_a_trip_price_from_reservation()
    {
        $reservation = $this->setupReservationWithPrices();
        $price = $reservation->priceables()->where('model_type', 'trips')->first();

        $response = $this->json('DELETE', "/api/reservations/{$reservation->id}/prices/{$price->uuid}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('priceables', [
            'price_id' => $price->id, 'priceable_id' => $reservation->id, 'priceable_type' => 'reservations'
        ]);
        $this->assertDatabaseHas('prices', ['id' => $price->id]);
    }

    /** @test */
    public function remove_a_custom_price_from_reservation()
    {
        $reservation = $this->setupReservationWithPrices();
        $price = $reservation->prices()->first();

        $response = $this->json('DELETE', "/api/reservations/{$reservation->id}/prices/{$price->uuid}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('priceables', [
            'price_id' => $price->id, 'priceable_id' => $reservation->id, 'priceable_type' => 'reservations'
        ]);
        $this->assertDatabaseMissing('prices', ['id' => $price->id]);
    }

    private function setupReservationWithPrices()
    {
        $trip = $this->setupTripWithPrices();

        $reservation = factory(Reservation::class)->create(['trip_id' => $trip->id]);
        $price = factory(Price::class)->create([
            'model_id' => $reservation->id, 
            'model_type' => 'reservations'
        ]);

        $tripPrices = $trip->priceables->pluck('id')->toArray();
        $reservation->priceables()->attach([
            $price->id, $tripPrices[0], $tripPrices[1]
        ]);

        return $reservation;
    }

    private function setupTripWithPrices()
    {
        $campaign = $this->setupCampaignWithCosts();

        $trip = factory(Trip::class)->create(['campaign_id' => $campaign->id]);
        $group = factory(CampaignGroup::class)->create(['campaign_id' => $campaign->id]);
        $groupPrice = factory(Price::class)->create([
            'model_id' => $group->id, 
            'model_type' => 'campaign-groups'
        ]);
        $tripPrice = factory(Price::class)->create([
            'model_id' => $trip->id, 
            'model_type' => 'trips'
        ]);
        
        $trip->priceables()->attach([$groupPrice->id, $tripPrice->id]);

        return $trip;
    }

    private function setupCampaignWithCosts()
    {
        $campaign = factory(Campaign::class)->create();
        factory(Cost::class, 2)->create([
            'cost_assignable_id' => $campaign->id, 
            'cost_assignable_type' => 'campaigns'
        ]);

        return $campaign;
    }
}
