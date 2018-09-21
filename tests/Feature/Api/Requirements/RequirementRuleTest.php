<?php

namespace Tests\Feature\Requirements;

use Tests\TestCase;
use App\Models\v1\Campaign;
use App\Models\v1\Requirement;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RequirementRuleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function add_rules_when_creating_a_requirement()
    {
        $campaign = factory(Campaign::class)->create();

        $data = [
            'name' => 'Passport',
            'short_desc' => 'A passport document is required to travel.',
            'document_type' => 'passport',
            'due_at' => '2019-05-01',
            'rules' => ['age' => '18', 'roles' => ['MEDI', 'MISS']],
            'upfront' => false
        ];

        $response = $this->postJson("/api/campaigns/{$campaign->id}/requirements", $data);

        $response->assertStatus(201);

        $requirement = Requirement::first();
        $this->assertEquals($requirement->rules, ['age' => '18', 'roles' => ['MEDI', 'MISS']]);
    }

    /** @test */
    public function add_rules_to_an_existing_requirement()
    {
        $campaign = factory(Campaign::class)->create();
        $requirement = factory(Requirement::class)->create(['requester_type' => 'campaigns', 'requester_id' => $campaign->id]);
        $campaign->requireables()->attach($requirement->id);

        $this->assertEmpty($requirement->rules);

        $response = $this->putJson("/api/campaigns/{$campaign->id}/requirements/{$requirement->id}", [
            'rules' => ['age' => '18', 'roles' => ['MEDI', 'MISS']]
        ]);

        $response->assertStatus(200)
          ->assertJson([
            'data' => [
                'rules' => [
                    'age' => '18', 'roles' => ['MEDI', 'MISS']
                ]
            ]
        ]);
    }

    /** @test */
    public function remove_rules_from_a_requirement()
    {
        $campaign = factory(Campaign::class)->create();
        $requirement = factory(Requirement::class)->create([
            'requester_type' => 'campaigns', 
            'requester_id' => $campaign->id,
            'rules' => ['age' => '18', 'roles' => ['MEDI', 'MISS']]
        ]);
        $campaign->requireables()->attach($requirement->id);

        $this->assertEquals($requirement->rules, ['age' => '18', 'roles' => ['MEDI', 'MISS']]);

        $response = $this->putJson("/api/campaigns/{$campaign->id}/requirements/{$requirement->id}", [
            'rules' => ['roles' => ['MEDI', 'MISS']]
        ]);

        $response->assertStatus(200)
          ->assertJson([
            'data' => [
                'rules' => [
                    'roles' => ['MEDI', 'MISS']
                ]
            ]
        ]);
    }

    /** @test */
    public function update_rules_for_a_requirement()
    {
        $this->assertTrue(true);
    }
}
