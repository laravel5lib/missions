<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\v1\Campaign;
use App\Models\v1\CampaignGroup;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CoordinatorScoreboardTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function get_registration_percentages_of_commitments_per_group_by_commitment_size_range()
    {
        $campaign = factory(Campaign::class)->create();

        factory(CampaignGroup::class, 10)->create([
            'campaign_id' => $campaign->id,
            'commitment' => 10
        ]);

        factory(CampaignGroup::class, 10)->create([
            'campaign_id' => $campaign->id,
            'commitment' => 50
        ]);

        $response = $this->getJson('/api/metrics/reservations/percent-of-commitment?filter[commitment]=1,25');

        $response->assertOk()
                 ->assertJsonStructure([
                    'data' => [
                        [
                            'id', 'group', 'commitment', 'reservations', 'percentage'
                        ]
                    ]
                 ])
                 ->assertJson([
                    'data' => [
                        ['commitment' => 10]
                    ]
                 ])
                 ->assertJsonMissing([
                    'data' => [
                        ['commitment' => 50]
                    ]
                 ]);
    }
}
