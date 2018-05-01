<?php

namespace Tests\Unit;

use Tests\TestCase;
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
}
