<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\v1\Group;
use App\Models\v1\Campaign;
use App\Models\v1\CampaignGroup;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CampaignGroupsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function gets_all_groups_for_a_campaign()
    {
        $group = factory(CampaignGroup::class)->create();

        $this->json('GET', "/api/campaigns/{$group->campaign_id}/groups")
             ->assertStatus(200)
             ->assertJsonStructure([
                 'data' => [
                     ['id', 'name', 'status', 'meta']
                 ]
             ]);
    }

    /** @test */
    public function adds_a_group_to_a_campaign()
    {
        $campaign = factory(Campaign::class)->create();
        $org = factory(Group::class)->create();

        $this->json('POST', "/api/campaigns/{$campaign->id}/groups", ['group_id' => $org->id, 'status_id' => 1])
             ->assertStatus(201);

        $this->assertDatabaseHas('campaign_group', [
            'group_id' => $org->id, 'campaign_id' => $campaign->id, 'status_id' => 1
        ]);
    }

    /** @test */
    public function updates_campaign_group()
    {
        $group = factory(CampaignGroup::class)->create(['status_id' => 1]);

        $this->json('PUT', "/api/campaigns/{$group->campaign_id}/groups/{$group->group_id}", ['status_id' => 2])
             ->assertStatus(200)
             ->assertJson([
                 'data' => [
                     'status' => 'Committed'
                 ]
             ]);

        $this->assertDatabaseHas('campaign_group', [
            'group_id' => $group->group_id, 'campaign_id' => $group->campaign_id, 'status_id' => 2
        ]);
    }
    
}
