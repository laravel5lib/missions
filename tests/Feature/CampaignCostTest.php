<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\v1\Campaign;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CampaignCostTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function get_all_costs_for_a_campaign()
    {
        $campaign = factory(Campaign::class)->create();
        
        $response = $this->json('GET', "/api/campaigns/{{ $campaign->id }}/costs");

        $response->assertStatus(200);
    }
}
