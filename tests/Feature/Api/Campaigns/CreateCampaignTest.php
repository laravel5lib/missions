<?php

namespace Tests\Feature\Api\Campaigns;

use Tests\TestCase;
use App\Models\v1\User;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateCampaignTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function create_new_campaign_and_save_in_storage()
    {
        Passport::actingAs(factory(User::class)->make());

        $data = [
            'name' => '1Nation1Day',
            'country_code' => 'pe',
            'started_at' => '2019-07-01',
            'ended_at' => '2019-07-07'
        ];

        $this->postJson("/api/campaigns", $data)->assertOk();

        $this->assertDatabaseHas('campaigns', $data);
    }
}
