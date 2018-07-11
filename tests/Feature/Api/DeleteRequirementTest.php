<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\v1\Campaign;
use App\Models\v1\Requirement;
use App\Models\v1\CampaignGroup;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteRequirementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function deletes_campaign_requirement_from_storage_and_removes_from_all_requireables()
    {
        $campaign = factory(Campaign::class)->create();
        $group = factory(CampaignGroup::class)->create(['campaign_id' => $campaign->id]);
        $requirement = factory(Requirement::class)->create([
            'requester_type' => 'campaigns', 'requester_id' => $campaign->id
        ]);
        $campaign->requireables()->attach($requirement->id);
        $group->requireables()->attach($requirement->id);

        $this->deleteJson("/api/campaigns/{$campaign->id}/requirements/{$requirement->id}")
             ->assertStatus(204);

        $this->assertDatabaseMissing('requirements', ['id' => $requirement->id]);
        $this->assertDatabaseMissing('requireables', ['requirement_id' => $requirement->id]);
    }
}
