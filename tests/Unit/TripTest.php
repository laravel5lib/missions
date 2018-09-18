<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\v1\Trip;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TripTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function updates_a_trips_spots_available()
    {
        $trip = factory(Trip::class)->create(['spots' => 25]);
        
        $trip->updateSpots(-1);

        $this->assertEquals($trip->spots, 24);
    }
}
