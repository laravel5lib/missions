<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FlightTest extends TestCase
{
    /** @test */
    public function gets_all_flights_for_a_campaign()
    {
        // FLIGHT_SEGMENTS
        // campaign_id
        // name

        // FLIGHTS
        // flight_segment_id
        // flight_no
        // date
        // time
        // iata_code
        // status (draft, published)

        // PASSENGERS
        // reservation_id
        // flight_id
        // record locator
        // type (individual, group)

        $this->assertTrue(true);
    }

    /** @test */
    public function creates_a_flight()
    {
        $this->assertTrue(true);
    }

    /** @test */
    public function updates_a_flight()
    {
        $this->assertTrue(true);
    }

    /** @test */
    public function deletes_a_flight()
    {
        $this->assertTrue(true);
    }
}
