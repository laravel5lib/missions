<?php

namespace Tests\Feature\Api\TripTemplates;

use Tests\TestCase;
use App\Models\v1\Trip;
use App\Models\v1\User;
use Laravel\Passport\Passport;
use App\Models\v1\TripTemplate;
use App\Models\v1\CampaignGroup;
use App\Models\v1\Representative;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateTripFromTemplateTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        Passport::actingAs(new User);
    }

    /** @test */
    public function it_creates_a_trip_from_a_template()
    {
        $group = factory(CampaignGroup::class)->create();
        $template = factory(TripTemplate::class)->create(['campaign_id' => $group->campaign_id]);
        $template->attachTags(['Amazon Region', 'Flight Included']);
        $rep = factory(Representative::class)->create();

        $formData = [
            'group_id' => $group->group_id,
            'campaign_id' => $group->campaign_id,
            'rep_id' => $rep->id,
            'template_id' => $template->id,
            'default_prices' => true,
            'default_requirements' => true,
        ];

        $response = $this->postJson("/api/trip-templates/trips", $formData);

        $response->assertStatus(201);

        $this->assertDatabaseHas('trips', [
            'type' => $template->type,
            'country_code' => $template->country_code,
            'group_id' => $group->group_id,
            'campaign_id' => $group->campaign_id,
            'rep_id' => $rep->id,
            'spots' => $template->spots,
            'companion_limit' => $template->companion_limit,
            'difficulty' => $template->difficulty,
            'started_at' => $template->started_at->toDateString(),
            'ended_at' => $template->ended_at->toDateString(),
            'todos' => castToJson($template->todos),
            'team_roles' => castToJson($template->team_roles),
            'prospects' => castToJson($template->prospects),
            'description' => $template->description,
            'public' => 0,
            'published_at' => null,
            'closed_at' => $template->closed_at->toDateTimeString()
        ]);

        $tags = Trip::first()->tags->pluck('name')->toArray();

        $this->assertContains('Amazon Region', $tags);
        $this->assertContains('Flight Included', $tags);
    }
}
