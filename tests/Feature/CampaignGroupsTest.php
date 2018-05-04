<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\v1\Group;
use App\Models\v1\Campaign;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CampaignGroupsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function gets_all_groups_for_a_campaign()
    {
        $campaign = factory(Campaign::class)->create();
        $org = factory(Group::class)->create();

        $campaign->groups()->attach($org->id);

        $this->json('GET', "/api/campaigns/{$campaign->id}/groups")
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

        $this->json('POST', "/api/campaigns/{$campaign->id}/groups", ['group_id' => $org->id])
             ->assertStatus(201);

        $this->assertDatabaseHas('campaign_group', ['group_id' => $org->id, 'campaign_id' => $campaign->id]);
    }
}