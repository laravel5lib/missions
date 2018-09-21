<?php

namespace Tests\Feature\Api\requirements;

use Tests\TestCase;
use App\Models\v1\Campaign;
use App\Models\v1\Requirement;
use App\Models\v1\CampaignGroup;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateRequirementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function create_requirement_for_campaign_and_save_in_storage()
    {
        $campaign = factory(Campaign::class)->create();
        
        $data = [
            'name' => 'Passport',
            'short_desc' => 'A passport document is required to travel.',
            'document_type' => 'passport',
            'due_at' => '2019-05-01',
            'upfront' => false
        ];

        $response = $this->postJson("/api/campaigns/{$campaign->id}/requirements", $data);

        $response->assertStatus(201);

        $this->assertDatabaseHas('requirements', [
            'name' => 'Passport', 'requester_id' => $campaign->id, 'requester_type' => 'campaigns'
        ]);
        $this->assertDatabaseHas('requireables', [
            'requireable_id' => $campaign->id, 'requireable_type' => 'campaigns'
        ]);
    }

    /** @test */
    public function create_upfront_requirement_for_campaign_and_save_in_storage()
    {
        $campaign = factory(Campaign::class)->create();
        
        $data = [
            'name' => 'Passport',
            'short_desc' => 'A passport document is required to travel.',
            'document_type' => 'passport',
            'upfront' => true
        ];

        $response = $this->postJson("/api/campaigns/{$campaign->id}/requirements", $data);

        $response->assertStatus(201);

        $this->assertDatabaseHas('requirements', [
            'name' => 'Passport', 'requester_id' => $campaign->id, 'requester_type' => 'campaigns', 'upfront' => 1
        ]);
    }

    /** @test */
    public function name_is_required_to_create_a_requirement()
    {
        $campaign = factory(Campaign::class)->create();
        
        $data = [
            'short_desc' => 'A passport document is required to travel.',
            'document_type' => 'passport',
            'due_at' => '2019-05-01',
            'upfront' => false
        ];

        $response = $this->postJson("/api/campaigns/{$campaign->id}/requirements", $data);

        $response->assertStatus(422)->assertJsonValidationErrors(['name']);
    }

    /** @test */
    public function name_must_unique_to_the_campaign_to_create_a_requirement()
    {
        $campaign = factory(Campaign::class)->create();
        factory(Requirement::class)->create([
            'name' => 'Passport', 'requester_type' => 'campaigns', 'requester_id' => $campaign->id
        ]);
        
        $data = [
            'name' => 'Passport',
            'short_desc' => 'A passport document is required to travel.',
            'document_type' => 'passport',
            'due_at' => '2019-05-01',
            'upfront' => false
        ];

        $response = $this->postJson("/api/campaigns/{$campaign->id}/requirements", $data);

        $response->assertStatus(422)->assertJsonValidationErrors(['name']);
    }

    /** @test */
    public function document_type_is_required_to_create_requirement()
    {
        $campaign = factory(Campaign::class)->create();
        
        $data = [
            'name' => 'Passport',
            'short_desc' => 'A passport document is required to travel.',
            'due_at' => '2019-05-01',
            'upfront' => false
        ];

        $response = $this->postJson("/api/campaigns/{$campaign->id}/requirements", $data);

        $response->assertStatus(422)->assertJsonValidationErrors(['document_type']);
    }

    /** @test */
    public function due_date_is_required_when_not_upfront_to_create_requirement()
    {
        $campaign = factory(Campaign::class)->create();
        
        $data = [
            'name' => 'Passport',
            'short_desc' => 'A passport document is required to travel.',
            'document_type' => 'passport',
            'upfront' => false
        ];

        $response = $this->postJson("/api/campaigns/{$campaign->id}/requirements", $data);

        $response->assertStatus(422)->assertJsonValidationErrors(['due_at']);
    }

    /** @test */
    public function add_campaign_requirement_to_group()
    {
        $campaign = factory(Campaign::class)->create();
        $requirement = factory(Requirement::class)->create(['requester_id' => $campaign->id, 'requester_type' => 'campaigns']);
        $campaign->requireables()->attach($requirement->id);
        $group = factory(CampaignGroup::class)->create(['campaign_id' => $campaign->id]);

        $this->postJson("/api/campaign-groups/{$group->uuid}/requirements", ['requirement_id' => $requirement->id])
             ->assertStatus(201);

        $this->assertDatabaseHas('requireables', [
            'requirement_id' => $requirement->id, 'requireable_id' => $group->uuid, 'requireable_type' => 'campaign-groups'
        ]);
    }
}
