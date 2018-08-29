<?php

namespace Tests\Feature\Api\Requirements;

use App\Models\v1\Campaign;
use App\Models\v1\CampaignGroup;
use App\Models\v1\Requirement;
use App\Models\v1\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class FetchRequirementTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        Passport::actingAs(factory(User::class)->create());
    }

   /** @test */
    public function fetchs_all_requirements_for_campaign()
    {
        $campaign = factory(Campaign::class)->create();

        $req = factory(Requirement::class)->create(['requester_type' => 'campaigns', 'requester_id' => $campaign->id]);
        factory(Requirement::class)->create();

        $campaign->requireables()->attach($req->id);

        $response = $this->getJson("/api/campaigns/{$campaign->id}/requirements");

        $response->assertStatus(200)
                 ->assertJsonStructure([
                    'data' => [
                        [
                            'id', 
                            'name', 
                            'short_desc', 
                            'document_type', 
                            'due_at', 
                            'grace_period', 
                            'requester' => ['id', 'type'], 
                            'created_at', 
                            'updated_at',
                            'status'
                        ]
                    ],
                    'meta' => ['total']
                 ])
                 ->assertJson([
                    'meta' => [ 'total' => 1]
                 ]);
    }

    /** @test */
    public function fetchs_all_requirements_for_campaign_group()
    {
        $group = factory(CampaignGroup::class)->create();

        // custom requirement
        $reqOne = factory(Requirement::class)->create(['requester_type' => 'campaign-groups', 'requester_id' => $group->uuid]);
        // campaign requirement
        $reqTwo = factory(Requirement::class)->create(['requester_type' => 'campaigns', 'requester_id' => $group->campaign_id]);

        $group->requireables()->attach([$reqOne->id, $reqTwo->id]);

        $response = $this->getJson("/api/campaign-groups/{$group->uuid}/requirements");

        $response->assertStatus(200)
                 ->assertJson([
                    'meta' => [ 'total' => 2]
                 ]);
    }

    /** @test */
    public function fetch_requirement_by_id()
    {
        $campaign = factory(Campaign::class)->create();
        $requirement = factory(Requirement::class)->create(['requester_type' => 'campaigns', 'requester_id' => $campaign->id]);
        $campaign->requireables()->attach($requirement->id);

        $response = $this->getJson("/api/campaigns/{$campaign->id}/requirements/{$requirement->id}");

        $response->assertStatus(200)
                 ->assertJson([
                    'data' => [
                        'id' => $requirement->id
                    ]
                 ]);
    }
}
