<?php

namespace Tests\Feature\Api\Trips;

use Tests\TestCase;
use App\Models\v1\Trip;
use App\Models\v1\User;
use App\Models\v1\Reservation;
use Laravel\Passport\Passport;
use App\Jobs\ApplyGroupPricing;
use App\Models\v1\CampaignGroup;
use App\Jobs\ApplyGroupRequirements;
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

        $this->json('GET', "/api/trips?filter[group_id]={$group->organization->id}&filter[campaign_id]={$group->campaign_id}")
          ->assertStatus(200)
          ->assertJson([
            'data' => [
                [
                    'id' => $trip->id,
                    'group_id' => $group->organization->id,
                    'campaign_id' => $group->campaign_id
                ]
            ],
            'meta' => [
                'total' => 1
            ]
          ]);
    }

    /** @test */
    public function get_trip_by_id()
    {
        $trip = factory(Trip::class)->create();

        $this->json('GET', "/api/trips/{$trip->id}")
            ->assertStatus(200)
            ->assertJson([
                'data' => ['id' => $trip->id]
            ]);
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

    /** @test */
    public function creates_new_trip_with_default_group_requirements()
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
            'default_requirements' => true,
            'team_roles' => ['MISS'],
            'prospects' => ['adults', 'young adults (18-29)', 'men', 'women']
        ])->assertStatus(201);

        Queue::assertPushed(ApplyGroupRequirements::class);

        $this->assertDatabaseHas('trips', [
            'campaign_id' => $group->campaign_id, 
            'group_id' => $group->group_id
        ]);
    }

    /** @test */
    public function updates_a_trip()
    {
        Passport::actingAs(factory(User::class, 'admin')->create());

        $trip = factory(Trip::class)->create([
            'type' => 'ministry', 
            'difficulty' => 'level_2'
        ]);

        $this->json('PUT', "/api/trips/{$trip->id}", [
            'type' => 'family',
            'difficulty' => 'level_1'
        ])
        ->assertStatus(200)
        ->assertJson([
            'data' => [
                'type' => 'family',
                'difficulty' => 'level_1'
            ]
        ]);
    }

    /** @test */
    public function campaign_and_group_ids_must_be_valid_to_create_trip()
    {
        Passport::actingAs(factory(User::class, 'admin')->create());
        
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
        Passport::actingAs(factory(User::class, 'admin')->create());
        
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

    /** @test */
    public function deletes_trip_and_drops_its_reservations()
    {
        Passport::actingAs(factory(User::class, 'admin')->create());

        $trip = factory(Trip::class)->create();
        $reservation = factory(Reservation::class)->create(['trip_id' => $trip->id]);
        $otherReservation = factory(Reservation::class)->create();

        $this->json('DELETE', "/api/trips/$trip->id")
             ->assertStatus(204);
        
        $this->assertDatabaseHas('trips', ['id' => $trip->id]);
        $this->assertDatabaseHas('reservations', ['id' => $reservation->id]);
        $this->assertNotNull($trip->fresh()->deleted_at);
        $this->assertNotNull($reservation->fresh()->deleted_at);
        $this->assertNull($otherReservation->fresh()->deleted_at);
    }
    
}
