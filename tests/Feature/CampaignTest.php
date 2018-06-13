<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\v1\Campaign;
use App\Models\v1\CampaignGroup;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CampaignTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function gets_all_campaigns()
    {
        factory(Campaign::class, 2)->create();

        $this->json('GET', '/api/campaigns')
             ->assertStatus(200)
             ->assertJsonStructure([
                'data' => [
                    [
                        'id', 
                        'name', 
                        'country' => ['name', 'code'], 
                        'description', 
                        'page_url', 
                        'page_src', 
                        'avatar', 
                        'started_at', 
                        'ended_at', 
                        'status',
                        'groups_count',
                        'reservations_locked',
                        'published_at',
                        'created_at',
                        'updated_at'
                    ]
                ],
                'meta' => ['total']
             ]);
    }

    /** @test */
    public function gets_all_campaigns_for_an_organization()
    {
        factory(CampaignGroup::class)->create();

        $group = factory(CampaignGroup::class)->create();

        $this->json('GET', "/api/campaigns?filter[organization]=$group->group_id")
             ->assertStatus(200)
             ->assertJson([
                 'data' => [ ['id' => $group->campaign_id] ],
                 'meta' => ['total' => 1]
             ]);
    }
}
