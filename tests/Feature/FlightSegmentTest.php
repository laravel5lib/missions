<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\v1\Campaign;
use App\Models\v1\FlightSegment;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FlightSegmentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function creates_a_new_flight_segment_for_campaign()
    {
        $campaign = factory(Campaign::class)->create();

        $this->json('POST', "/api/campaigns/{$campaign->id}/flights/segments", [
            'name' => 'Departure to Destination'
        ])->assertStatus(201);

        $this->assertDatabaseHas('flight_segments', ['name' => 'Departure to Destination']);
    }

    /** @test */
    public function requires_a_unique_name_to_create_a_flight_segment()
    {
        $campaign = factory(Campaign::class)->create();
        factory(FlightSegment::class)->create([
            'name' => 'Departure to Destination', 
            'campaign_id' => $campaign->id
        ]);

        $this->json('POST', "/api/campaigns/{$campaign->id}/flights/segments", [
            'name' => 'Departure to Destination'
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['name']);
    }

    /** @test */
    public function gets_all_flight_segments_for_a_campaign()
    {
        $campaign = factory(Campaign::class)->create();
        factory(FlightSegment::class, 4)->create([
            'campaign_id' => $campaign->id
        ]);

        $this->json('GET', "/api/campaigns/{$campaign->id}/flights/segments", [
            'name' => 'Departure to Destination'
        ])
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                ['id', 'name', 'created_at', 'updated_at']
            ]
        ]);
    }

    /** @test */
    public function updates_a_flight_segment()
    {
        $campaign = factory(Campaign::class)->create();
        $segment = factory(FlightSegment::class)->create([
            'campaign_id' => $campaign->id,
            'name' => 'Departure to Destination'
        ]);

        $this->json('PUT', "/api/campaigns/{$campaign->id}/flights/segments/{$segment->uuid}", [
            'name' => 'Destination Departure'
        ])
        ->assertStatus(200)
        ->assertJson([
            'data' => [
                'id' => $segment->uuid,
                'name' => 'Destination Departure'
            ]
        ]);
    }

    /** @test */
    public function deletes_a_flight_segment()
    {
        $campaign = factory(Campaign::class)->create();
        $segment = factory(FlightSegment::class)->create([
            'campaign_id' => $campaign->id
        ]);

        $this->json('DELETE', "/api/campaigns/{$campaign->id}/flights/segments/{$segment->uuid}")
             ->assertStatus(204);
        
        $this->assertDatabaseMissing('flight_segments', ['id' => $segment->id]);
    }
}
