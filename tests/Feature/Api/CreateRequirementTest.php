<?php

namespace Tests\Feature\Api;

use App\Models\v1\Campaign;
use App\Models\v1\Requirement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

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
            'requester_type' => 'campaigns',
            'requester_id' => $campaign->id
        ];

        $response = $this->postJson('/api/requirements', $data);

        $response->assertStatus(201);

        $this->assertDatabaseHas('requirements', [
            'name' => 'Passport', 'requester_id' => $campaign->id, 'requester_type' => 'campaigns'
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
            'requester_type' => 'campaigns',
            'requester_id' => $campaign->id
        ];

        $response = $this->postJson('/api/requirements', $data);

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
            'requester_type' => 'campaigns',
            'requester_id' => $campaign->id
        ];

        $response = $this->postJson('/api/requirements', $data);

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
            'requester_type' => 'campaigns',
            'requester_id' => $campaign->id
        ];

        $response = $this->postJson('/api/requirements', $data);

        $response->assertStatus(422)->assertJsonValidationErrors(['document_type']);
    }

    /** @test */
    public function due_date_is_required_to_create_requirement()
    {
        $campaign = factory(Campaign::class)->create();
        
        $data = [
            'name' => 'Passport',
            'short_desc' => 'A passport document is required to travel.',
            'document_type' => 'passport',
            'requester_type' => 'campaigns',
            'requester_id' => $campaign->id
        ];

        $response = $this->postJson('/api/requirements', $data);

        $response->assertStatus(422)->assertJsonValidationErrors(['due_at']);
    }

    /** @test */
    public function requester_type_and_id_is_required_to_create_requirement()
    {
        $campaign = factory(Campaign::class)->create();
        
        $data = [
            'name' => 'Passport',
            'short_desc' => 'A passport document is required to travel.',
            'document_type' => 'passport',
            'due_at' => '2019-05-01',
        ];

        $response = $this->postJson('/api/requirements', $data);

        $response->assertStatus(422)->assertJsonValidationErrors(['requester_type', 'requester_id']);
    }
}
