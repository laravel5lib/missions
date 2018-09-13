<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\v1\Campaign;
use App\Models\v1\TripTemplate;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewTripTemplateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function get_all_trip_templates_for_a_campaign()
    {
        $campaign = factory(Campaign::class)->create();
        factory(TripTemplate::class)->create(['campaign_id' => $campaign->id]);

        $response = $this->getJson("/api/campaigns/{$campaign->id}/trip-templates");

        $response->assertOk()->assertJsonStructure([
            'data' => [
                [
                    'id', 'name', 'campaign_id', 'spots', 'companion_limit', 'country_code', 
                    'type', 'difficulty', 'started_at', 'ended_at', 'todos', 'prospects', 
                    'team_roles', 'description', 'closed_at', 'created_at', 'updated_at'
                ]
            ],
            'meta'
        ]);
    }
}
