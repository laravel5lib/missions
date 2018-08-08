<?php

namespace Tests\Feature\Api\Campaigns;

use Tests\TestCase;
use App\Models\v1\User;
use App\Models\v1\Campaign;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateCampaignTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function update_campaign_and_save_changes_in_storage()
    {
        Passport::actingAs(factory(User::class)->create());

        $campaign = factory(Campaign::class)->create(['name' => '1Nation1Day']);

        $this->putJson("/api/campaigns/{$campaign->id}", ['name' => '1Nation1Day 2019'])
             ->assertOk()
             ->assertJson([
                'data' => [
                    'name' => '1Nation1Day 2019'
                ]
             ]);

        $this->assertDatabaseHas('campaigns', ['id' => $campaign->id, 'name' => '1Nation1Day 2019']);
    }

    /** @test */
    public function update_campaign_page_url()
    {
        Passport::actingAs(factory(User::class)->create());

        $campaign = factory(Campaign::class)->create(['name' => '1Nation1Day']);
        $campaign->slug()->create(['url' => '1nation1day']);

        $this->putJson("/api/campaigns/{$campaign->id}", ['page_url' => '1nation1day2019'])
             ->assertOk()
             ->assertJson([
                'data' => [
                    'page_url' => '1nation1day2019'
                ]
             ]);

        $this->assertDatabaseHas(
            'slugs', 
            [
                'slugable_type' => 'campaigns', 
                'slugable_id' => $campaign->id, 
                'url' => '1nation1day2019'
            ]
        );
        $this->assertDatabaseMissing(
            'slugs', 
            [
                'slugable_type' => 'campaigns', 
                'slugable_id' => $campaign->id, 
                'url' => '1nation1day'
            ]
        );
    }
}
