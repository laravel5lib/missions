<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\v1\Flight;
use App\Models\v1\Reservation;
use App\Models\v1\FlightItinerary;
use Illuminate\Support\Facades\Queue;
use App\Jobs\NotifyOfPublishedFlights;
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

    /** @test */
    public function sends_notifications_when_itinerary_is_published()
    {
        $itinerary = factory(FlightItinerary::class)->create();
        factory(Flight::class, 2)->create(['flight_itinerary_id' => $itinerary->id]);
        factory(Reservation::class, 2)->create(['flight_itinerary_id' => $itinerary->id]);

        Queue::fake();

        $this->json('PUT', '/api/flights/itineraries/published', [
            'itineraries' => [$itinerary->uuid],
            'published' => true
        ])
        ->assertStatus(200);

        Queue::assertPushed(NotifyOfPublishedFlights::class);
    }

    /** @test */
    public function does_not_send_notifications_when_itinerary_is_unpublished()
    {
        $itinerary = factory(FlightItinerary::class)->create(['published' => true]);
        factory(Flight::class, 2)->create(['flight_itinerary_id' => $itinerary->id]);
        factory(Reservation::class, 2)->create(['flight_itinerary_id' => $itinerary->id]);

        Queue::fake();

        $this->json('PUT', '/api/flights/itineraries/published', [
            'itineraries' => [$itinerary->uuid],
            'published' => false
        ])
        ->assertStatus(200);

        Queue::assertNotPushed(NotifyOfPublishedFlights::class);
    }
}
