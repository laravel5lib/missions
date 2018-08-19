<?php

namespace Tests\Feature\Api\Trips;

use Tests\TestCase;
use App\Models\v1\Trip;
use App\Models\v1\User;
use App\Models\v1\Reservation;
use Laravel\Passport\Passport;
use App\Jobs\ApplyGroupPricing;
use App\Models\v1\CampaignGroup;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateTripTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        Passport::actingAs(factory(User::class, 'admin')->make());
    }

    /** @test */
    public function creates_new_trip_with_default_group_prices()
    { 
        $group = factory(CampaignGroup::class)->make();

        Queue::fake();

        $this->json('POST', '/api/trips', [
            'campaign_id' => $group->campaign_id,
            'group_id' => $group->group_id,
            'country_code' => $group->campaign->country_code,
            'type' => 'ministry',
            'difficulty' => 'level_1',
            'started_at' => $group->campaign->started_at->toDateString(),
            'ended_at' => $group->campaign->ended_at->toDateString(),
            'companion_limit' => 0,
            'spots' => 25,
            'closed_at' => null,
            'default_prices' => true,
            'team_roles' => ['MISS'],
            'prospects' => ['adults', 'young adults (18-29)', 'men', 'women']
        ])->assertStatus(201);

        Queue::assertPushed(ApplyGroupPricing::class);

        $this->assertDatabaseHas('trips', [
            'campaign_id' => $group->campaign_id, 
            'group_id' => $group->group_id
        ]);
    }

    /** @test */
    public function create_trip_with_tags()
    {
        $tags = ['flight included', 'amazon region'];

        $data = array_merge(factory(Trip::class)->make()->toArray(), ['tags' => $tags]);

        $response = $this->json('POST', '/api/trips', $data);

        $response->assertStatus(201);

        $this->assertEquals(Trip::first()->tagsWithType('trip')->count(), 2);
    }

    /** @test */
    public function campaign_and_group_ids_must_be_valid_to_create_trip()
    {       
        $group = factory(CampaignGroup::class)->create();

        $this->json('POST', '/api/trips', [
            'campaign_id' => 'invalid',
            'group_id' => 'invalid',
            'country_code' => $group->campaign->country_code,
            'type' => 'ministry',
            'difficulty' => 'level_1',
            'started_at' => $group->campaign->started_at->toDateString(),
            'ended_at' => $group->campaign->ended_at->toDateString(),
            'companion_limit' => 0,
            'spots' => 25,
            'closed_at' => null,
            'default_prices' => false,
            'team_roles' => ['MISS'],
            'prospects' => ['adults', 'young adults (18-29)', 'men', 'women']
        ])
        ->assertJsonValidationErrors([
            'campaign_id', 'group_id'
        ]);
    }

    /** @test */
    public function rep_id_must_be_valid_to_create_trip()
    {     
        $group = factory(CampaignGroup::class)->create();

        $this->json('POST', '/api/trips', [
            'rep_id' => 'invalid',
            'campaign_id' => $group->campaign_id,
            'group_id' => $group->group_id,
            'country_code' => $group->campaign->country_code,
            'type' => 'ministry',
            'difficulty' => 'level_1',
            'started_at' => $group->campaign->started_at->toDateString(),
            'ended_at' => $group->campaign->ended_at->toDateString(),
            'companion_limit' => 0,
            'spots' => 25,
            'closed_at' => null,
            'default_prices' => true,
            'team_roles' => ['MISS'],
            'prospects' => ['adults', 'young adults (18-29)', 'men', 'women']
        ])
        ->assertJsonValidationErrors([
            'rep_id'
        ]);
    }
}
