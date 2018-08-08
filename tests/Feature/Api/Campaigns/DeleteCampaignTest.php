<?php

namespace Tests\Feature\Api\Campaigns;

use Tests\TestCase;
use App\Models\v1\Trip;
use App\Models\v1\User;
use App\Models\v1\Campaign;
use App\Models\v1\Reservation;
use Laravel\Passport\Passport;
use App\Models\v1\CampaignGroup;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteCampaignTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function deletes_campaign_removes_groups_deletes_trips_and_drops_reservations()
    {
        Passport::actingAs(factory(User::class)->create());
        
        $group = factory(CampaignGroup::class)->create();
        $campaign = Campaign::findOrFail($group->campaign_id);
        $trip = factory(Trip::class)->create(['campaign_id' => $group->campaign_id, 'group_id' => $group->group_id]);
        $reservation = factory(Reservation::class)->create(['trip_id' => $trip->id]);

        $this->json('DELETE', "/api/campaigns/$group->campaign_id")->assertStatus(204);

        $this->assertNotNull($campaign->fresh()->deleted_at);
        $this->assertNotNull($trip->fresh()->deleted_at);
        $this->assertNotNull($reservation->fresh()->deleted_at);

        $this->assertDatabaseMissing('campaign_group', [
            'campaign_id' => $group->campaign_id, 'group_id' => $group->group_id
        ]);
    }
}
