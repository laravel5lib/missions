<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\v1\Flight;
use App\Models\v1\FlightSegment;
use App\Models\v1\FlightItinerary;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FlightTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function gets_all_flights_for_a_flight_segment()
    {
        $segment = factory(FlightSegment::class)->create();
        $itinerary = factory(FlightItinerary::class)->create();

        factory(Flight::class, 3)->create([
            'flight_segment_id' => $segment->id, 'flight_itinerary_id' => $itinerary->id
        ]);

        $this->json('GET', "/api/flights?filter[segment]={$segment->uuid}")
             ->assertStatus(200)
             ->assertJsonStructure([
                 'data' => [
                    [
                        'id', 'flight_no', 'date', 'time', 'iata_code', 
                        'created_at', 'updated_at'
                    ]
                 ],
                 'meta'
             ]);
    }

    /** @test */
    public function gets_all_flights_for_a_flight_itinerary()
    {
        $segment = factory(FlightSegment::class)->create();
        $itinerary = factory(FlightItinerary::class)->create();

        factory(Flight::class, 3)->create([
            'flight_segment_id' => $segment->id, 'flight_itinerary_id' => $itinerary->id
        ]);

        $this->json('GET', "/api/flights?filter[itinerary]={$itinerary->uuid}")
             ->assertStatus(200)
             ->assertJsonStructure([
                 'data' => [
                    [
                        'id', 'flight_no', 'date', 'time', 'iata_code', 
                        'created_at', 'updated_at'
                    ]
                 ],
                 'meta'
             ]);
    }

    /** @test */
    public function gets_a_flight_by_its_uuid()
    {
        $flight = factory(Flight::class)->create();

        $this->json('GET', "/api/flights/{$flight->uuid}")
             ->assertStatus(200)
             ->assertJson([
                 'data' => [
                     'id' => $flight->uuid
                 ]
             ]);
    }

    /** @test */
    public function creates_a_flight()
    {
        $segment = factory(FlightSegment::class)->create();
        $itinerary = factory(FlightItinerary::class)->create();

        $this->json('POST', "/api/flights", [
            'flight_segment_id' => $segment->id,
            'flight_itinerary_id' => $itinerary->id,
            'flight_no' => 'DL400',
            'date' => '2018-07-01',
            'time' => '22:00',
            'iata_code' => 'SNA'
        ])->assertStatus(201);
        
        $this->assertDatabaseHas('flights', [
            'flight_segment_id' => $segment->id,
            'flight_itinerary_id' => $itinerary->id,
            'flight_no' => 'DL400',
            'date' => '2018-07-01',
            'time' => '22:00:00',
            'iata_code' => 'SNA'
        ]);
    }

    /** @test */
    public function updates_a_flight()
    {
        $flight = factory(Flight::class)->create([
            'flight_no' => 'DL400',
            'date' => '2018-07-01',
            'time' => '22:00',
            'iata_code' => 'SNA'
        ]);

        $this->json('PUT', "/api/flights/{$flight->uuid}", [
            'flight_no' => 'DL200',
            'date' => '2018-07-02',
            'time' => '15:00'
        ])
        ->assertStatus(200)
        ->assertJson([
            'data' => [
                'id' => $flight->uuid,
                'flight_no' => 'DL200',
                'date' => '2018-07-02',
                'time' => '15:00',
                'iata_code' => 'SNA'
            ]
        ]);

        $this->assertDatabaseHas('flights', [
            'id' => $flight->id,
            'uuid' => $flight->uuid,
            'flight_segment_id' => $flight->flight_segment_id,
            'flight_itinerary_id' => $flight->flight_itinerary_id,
            'flight_no' => 'DL200',
            'date' => '2018-07-02',
            'time' => '15:00:00',
            'iata_code' => 'SNA'
        ]);
    }

    /** @test */
    public function deletes_a_flight()
    {
        $flight = factory(Flight::class)->create();

        $this->json('DELETE', "/api/flights/{$flight->uuid}")->assertStatus(204);

        $this->assertDatabaseMissing('flights', ['id' => $flight->id]);
    }
}
