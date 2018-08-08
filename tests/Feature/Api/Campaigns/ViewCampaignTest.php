<?php

namespace Tests\Feature\Api\Campaigns;

use Tests\TestCase;
use App\Models\v1\Campaign;
use App\Models\v1\CampaignGroup;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewCampaignTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function get_all_campaigns()
    {
        factory(Campaign::class, 2)->create();

        $this->getJson('/api/campaigns')
             ->assertOk()
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
    public function get_all_campaigns_for_an_organization()
    {
        factory(CampaignGroup::class)->create();

        $group = factory(CampaignGroup::class)->create();

        $this->json('GET', "/api/campaigns?filter[organization]=$group->group_id")
             ->assertOk()
             ->assertJson([
                 'data' => [ ['id' => $group->campaign_id] ],
                 'meta' => ['total' => 1]
             ]);
    }

    /** @test */
    public function get_campaign_by_id()
    {
        $campaign = factory(Campaign::class)->create(['name' => '1Nation1Day']);

        $this->getJson("/api/campaigns/{$campaign->id}")
             ->assertOk()
             ->assertJson([
                'data' => [
                    'name' => '1Nation1Day'
                ]
             ]);
    }

    /** @test */
    public function get_campaign_by_slug()
    {
        $campaign = factory(Campaign::class)->create(['name' => '1Nation1Day']);
        $campaign->slug()->create(['url' => '1nation1day']);

        $this->getJson("/api/campaigns/{$campaign->slug->url}")
             ->assertOk()
             ->assertJson([
                'data' => [
                    'name' => '1Nation1Day',
                    'page_url' => '1nation1day'
                ]
             ]);
    }
}
