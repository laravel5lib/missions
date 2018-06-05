<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\v1\Trip;
use App\Models\v1\Reservation;
use App\Models\v1\FlightSegment;
use App\Models\v1\FlightItinerary;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FlightItineraryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function gets_all_itineraries_for_a_campaign()
    {
        $reservation = factory(Reservation::class)->create();
        $itinerary = factory(FlightItinerary::class)->create();
        $reservation->flight_itinerary_id = $itinerary->id;
        $reservation->save();

        $this->json('GET', "/api/campaigns/{$reservation->trip->campaign_id}/flights/itineraries")
             ->assertStatus(200)
             ->assertJsonStructure([
                 'data' => [
                     ['id', 'type', 'record_locator', 'created_at', 'updated_at']
                 ]
             ]);
    }

    /** @test */
    public function searches_itineraries_by_record_locator()
    {
        $trip = factory(Trip::class)->create();
        
        $otherItinerary = factory(FlightItinerary::class)->create(['record_locator' => 'FJ3N2C']);
        $otherReservation = factory(Reservation::class)->create(['trip_id' => $trip->id]);
        $otherReservation->flight_itinerary_id = $otherItinerary->id;
        $otherReservation->save();

        $reservation = factory(Reservation::class)->create(['trip_id' => $trip->id]);
        $itinerary = factory(FlightItinerary::class)->create(['record_locator' => 'QX4R6M']);
        $reservation->flight_itinerary_id = $itinerary->id;
        $reservation->save();

        $this->json('GET', "/api/campaigns/{$reservation->trip->campaign_id}/flights/itineraries?filter[record_locator]=QX")
             ->assertStatus(200)
             ->assertJson([
                 'data' => [
                     [
                         'id' => $itinerary->uuid,
                         'record_locator' => 'QX4R6M'
                     ]
                 ]
             ])
             ->assertJsonMissing([
                 'data' => [
                     [
                         'record_locator' => 'FJ3N2C'
                     ]
                 ]
             ]);
    }

    /** @test */
    public function creates_itinerary_for_reservations_with_flights()
    {
        $trip = factory(Trip::class)->create();
        factory(Reservation::class, 2)->create(['trip_id' => $trip->id]);
        $reservationIds = Reservation::pluck('id')->toArray();

        $this->json('POST', "/api/campaigns/{$trip->campaign_id}/flights/itineraries", [
            'type' => 'individual',
            'record_locator' => 'RX2Q3F',
            'reservations' => $reservationIds,
            'flights' => [
                [
                    'flight_segment_id' => factory(FlightSegment::class)->create()->id,
                    'iata_code' => 'SNA',
                    'date' => today()->addMonth()->toDateString(),
                    'time' => '12:00'
                ],
                [
                    'flight_segment_id' => factory(FlightSegment::class)->create()->id,
                    'iata_code' => 'MGA',
                    'date' => today()->addMonths(2)->toDateString(),
                    'time' => '22:00'
                ]
            ]
        ])->assertStatus(201);

        $this->assertDatabaseHas('flight_itineraries', ['record_locator' => 'RX2Q3F']);
        $this->assertDatabaseHas('flights', ['iata_code' => 'SNA']);
        $this->assertDatabaseHas('flights', ['iata_code' => 'MGA']);
    }
}
