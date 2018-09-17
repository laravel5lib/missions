<?php

namespace Tests\Feature\Api\TripTemplates;

use Tests\TestCase;
use App\Models\v1\User;
use App\Models\v1\Campaign;
use Laravel\Passport\Passport;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateTripTemplateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_create_new_trip_template_for_a_campaign()
    {   
        $campaign = factory(Campaign::class)->create();
        $user = factory(User::class)->create();
        $user->assignRole(Role::create(['name' => 'admin'])->name);
        Passport::actingAs($user);

        $data = [
            'name'            => 'Amazon Region Ministry Trip',
            'country_code'    => 'pe',
            'type'            => 'ministry',
            'difficulty'      => 'level_1',
            'started_at'      => today()->addMonths(6)->toDateString(),
            'ended_at'        => today()->addMonths(6)->addDays(7)->toDateString(),
            'team_roles'      => ['MISS', 'MEDI'],
            'spots'           => 25,
            'todos'           => ['Welcome Call', 'Welcome Email'],
            'prospects'       => ['Adults', 'Men', 'Women'],
            'description'     => '### What to expected',
            'companion_limit' => 3,
            'closed_at'       => today()->addMonths(5)->toDateTimeString(),
            'tags'            => ['location: amazon region', 'flight included']
        ];

        $response = $this->postJson("/api/campaigns/{$campaign->id}/trip-templates", $data);

        $response->assertStatus(201);
    }
}
