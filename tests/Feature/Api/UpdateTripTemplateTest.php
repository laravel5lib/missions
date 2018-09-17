<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\v1\User;
use Laravel\Passport\Passport;
use App\Models\v1\TripTemplate;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateTripTemplateTest extends TestCase
{
   use RefreshDatabase;

    /** @test */
    public function admin_can_update_trip_template()
    {
        $user = factory(User::class)->create();
        $user->assignRole(Role::create(['name' => 'admin'])->name);
        Passport::actingAs($user);
        
        $template = factory(TripTemplate::class)->create(['name' => 'Created Name']);

        $data = [
            'name' => 'Updated Name'
        ];

        $response = $this->putJson("/api/campaigns/{$template->campaign_id}/trip-templates/{$template->id}", $data);

        $response->assertOk()->assertJson(['data' => ['name' => 'Updated Name']]);
    }
}
