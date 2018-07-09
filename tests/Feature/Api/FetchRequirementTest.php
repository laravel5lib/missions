<?php

namespace Tests\Feature\Api;

use App\Models\v1\Campaign;
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

        factory(Requirement::class, 2)->create(['requester_type' => 'campaigns', 'requester_id' => $campaign->id]);
        factory(Requirement::class)->create();

        $response = $this->getJson("/api/requirements?filter[campaign_id]={$campaign->id}");

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
                            'updated_at'
                        ]
                    ],
                    'meta' => ['total']
                 ])
                 ->assertJson([
                    'meta' => [ 'total' => 2]
                 ]);
    }
}
