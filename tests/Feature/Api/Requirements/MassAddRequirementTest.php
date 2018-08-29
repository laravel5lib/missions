<?php

namespace Tests\Feature\Requirements;

use Tests\TestCase;
use App\Models\v1\Trip;
use App\Models\v1\Campaign;
use App\Models\v1\Requirement;
use App\Models\v1\Reservation;
use App\Models\v1\CampaignGroup;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MassAddRequirementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function add_campaign_requirement_to_all_child_resources()
    {
        $campaign = factory(Campaign::class)->create();
        $requirement = factory(Requirement::class)->create(['requester_type' => 'campaigns', 'requester_id' => $campaign->id]);
        $campaign->requireables()->attach($requirement->id);
        $group = factory(CampaignGroup::class)->create(['campaign_id' => $campaign->id]);
        $trip = factory(Trip::class)->create(['campaign_id' => $campaign->id, 'group_id' => $group->group_id]);
        $reservation = factory(Reservation::class)->create(['trip_id' => $trip->id]);

        $response = $this->postJson(
            "/api/campaigns/{$campaign->id}/requirements/{$requirement->id}/add", 
            ['groups' => true, 'trips' => true, 'reservations' => true]
        );

        $response->assertStatus(201);

        $this->assertTrue($group->requireables()->first()->is($requirement));
        $this->assertTrue($trip->requireables()->first()->is($requirement));
        $this->assertTrue($reservation->requireables()->first()->is($requirement));
    }

    /** @test */
    public function add_group_requirement_to_all_child_resources()
    {
        $group = factory(CampaignGroup::class)->create();
        $requirement = factory(Requirement::class)->create(['requester_type' => 'campaign-groups', 'requester_id' => $group->uuid]);
        $group->requireables()->attach($requirement->id);
        $trip = factory(Trip::class)->create(['campaign_id' => $group->campaign_id, 'group_id' => $group->group_id]);
        $reservation = factory(Reservation::class)->create(['trip_id' => $trip->id]);

        $response = $this->postJson(
            "/api/campaign-groups/{$group->uuid}/requirements/{$requirement->id}/add", 
            ['trips' => true, 'reservations' => true]
        );

        $response->assertStatus(201);

        $this->assertTrue($trip->requireables()->first()->is($requirement));
        $this->assertTrue($reservation->requireables()->first()->is($requirement));
    }

    /** @test */
    public function add_trip_requirement_to_all_reservations()
    {
        $trip = factory(Trip::class)->create();
        $requirement = factory(Requirement::class)->create(['requester_type' => 'trips', 'requester_id' => $trip->id]);
        $trip->requireables()->attach($requirement->id);
        $reservation = factory(Reservation::class)->create(['trip_id' => $trip->id]);

        $response = $this->postJson(
            "/api/trips/{$trip->id}/requirements/{$requirement->id}/add", 
            ['trips' => true, 'reservations' => true]
        );

        $response->assertStatus(201);

        $this->assertTrue($trip->requireables()->first()->is($requirement));
        $this->assertTrue($reservation->requireables()->first()->is($requirement));
    }
}
