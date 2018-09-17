<?php

namespace Tests\Feature\Api\TripTemplates;

use Tests\TestCase;
use App\Models\v1\User;
use App\Models\v1\Campaign;
use Laravel\Passport\Passport;
use App\Models\v1\TripTemplate;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewTripTemplateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function get_all_trip_templates_for_a_campaign()
    {
        $user = factory(User::class)->create();
        $user->assignRole(Role::create(['name' => 'admin'])->name);
        Passport::actingAs($user);

        $campaign = factory(Campaign::class)->create();
        factory(TripTemplate::class)->create(['campaign_id' => $campaign->id]);

        $response = $this->getJson("/api/campaigns/{$campaign->id}/trip-templates");

        $response->assertOk()->assertJsonStructure([
            'data' => [
                [
                    'id', 'name', 'campaign_id', 'spots', 'companion_limit', 'country_code', 
                    'type', 'difficulty', 'started_at', 'ended_at', 'todos', 'prospects', 
                    'team_roles', 'description', 'closed_at', 'created_at', 'updated_at'
                ]
            ],
            'meta'
        ]);
    }

    /** @test */
    public function get_trip_template_by_id()
    {
        $user = factory(User::class)->create();
        $user->assignRole(Role::create(['name' => 'admin'])->name);
        Passport::actingAs($user);

        $campaign = factory(Campaign::class)->create();
        $template = factory(TripTemplate::class)->create(['campaign_id' => $campaign->id]);

        $response = $this->getJson("/api/campaigns/{$campaign->id}/trip-templates/{$template->id}");

        $response->assertOk()->assertJson([
            'data' => [
                'id' => $template->id
            ]
        ]);
    }

    /** @test */
    public function only_admins_can_view_trip_templates()
    {
        Passport::actingAs(factory(User::class)->make());

        $campaign = factory(Campaign::class)->create();
        $response = $this->getJson("/api/campaigns/{$campaign->id}/trip-templates/");

        $response->assertStatus(500);
    }
}
