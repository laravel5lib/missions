<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\v1\FlightItinerary;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FlightItineraryPublicationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function publishes_itineraries()
    {
        factory(FlightItinerary::class, 2)->create();
        
        $itineraries = FlightItinerary::all();

        $this->json('PUT', '/api/flights/itineraries/published', [
            'itineraries' => $itineraries->pluck('uuid'),
            'published' => true
        ])
        ->assertStatus(200);
        
        $this->assertDatabaseHas('flight_itineraries', ['published' => 1]);
        $this->assertEquals(FlightItinerary::where('published', true)->count(), 2);
    }

    /** @test */
    public function unpublishes_itineraries()
    {
        factory(FlightItinerary::class, 2)->create(['published' => true]);
        
        $itineraries = FlightItinerary::all();

        $this->json('PUT', '/api/flights/itineraries/published', [
            'itineraries' => $itineraries->pluck('uuid'),
            'published' => false
        ])
        ->assertStatus(200);

        $this->assertDatabaseHas('flight_itineraries', ['published' => 0]);
        $this->assertEquals(FlightItinerary::where('published', false)->count(), 2);
    }

    public function sends_notifications_when_itinerary_is_published()
    {
        // sends email to all passengers
        // sends email to team coordinators
    }
}
