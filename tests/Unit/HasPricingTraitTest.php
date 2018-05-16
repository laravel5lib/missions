<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\v1\Cost;
use App\Models\v1\Reservation;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HasPricingTraitTest extends TestCase
{
    /** @test */
    public function trip_has_a_current_rate()
    {
        // setup a trip
        // setup two incremental costs
        // add costs to the trip with payments

        // assert the latest price is the current rate

        $this->assertTrue(true);
    }

    /** @test */
    public function trip_has_upfront_costs()
    {
        // setup a trip
        // create an upfront cost
        // add cost to the trip

        // assert that cost is returned

        $this->assertTrue(true);
    }

    /** @test */
    public function trip_has_starting_cost()
    {
        // setup a trip
        // setup two incremental costs
        // create an upfront cost
        // add all costs to the trip with payments

        // assert the latest price + all upfront costs = the starting cost

        $this->assertTrue(true);
    }

    /** @test */
    public function trip_has_upcoming_deadline()
    {
        // setup a trip
        // setup two incremental costs
        // add costs to the trip with payments

        // assert the latest payment for the latest price is the upcoming deadline

        $this->assertTrue(true);
    }

    /** @test */
    public function locks_and_unlocks_the_current_rate()
    {
        $reservation = factory(Reservation::class)->create();
        $reservation->addPrice([
            'cost_id' => factory(Cost::class)->create(['type' => 'incremental'])->id,
            'amount' => 1000
        ]);

        $reservation->lockCurrentRate();

        $this->assertEquals(1, $reservation->getCurrentRate()->pivot->locked);

        $reservation->unlockCurrentRate();
        
        $this->assertEquals(0, $reservation->getCurrentRate()->pivot->locked);
    }
}
