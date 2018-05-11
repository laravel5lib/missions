<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\v1\Trip;
use App\Models\v1\User;
use Laravel\Passport\Passport;
use App\Jobs\ApplyGroupPricing;
use App\Models\v1\CampaignGroup;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TripTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function get_all_trips_for_a_group()
    {
        $otherTrips = factory(Trip::class, 4)->create();

        $group = factory(CampaignGroup::class)->create();
        $trip = factory(Trip::class)->create([
            'campaign_id' => $group->campaign_id, 'group_id' => $group->organization->id
        ]);

        $this->json('GET', '/api/trips', [
            'filters' => [
                'group_id' => $group->organization->id, 
                'campaign_id' => $group->campaign_id
            ]
        ])->assertStatus(200);
    }

    /** @test */
    public function creates_new_trip_with_default_group_prices()
    {
        Passport::actingAs(factory(User::class, 'admin')->create());
        
        $group = factory(CampaignGroup::class)->create();

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
}
