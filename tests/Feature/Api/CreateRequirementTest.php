<?php

namespace Tests\Feature\Api;

use App\Models\v1\Campaign;
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
}
