<?php

namespace Tests\Feature\Api\TripTemplates;

use Tests\TestCase;
use App\Models\v1\User;
use Laravel\Passport\Passport;
use App\Models\v1\TripTemplate;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteTripTemplateTest extends TestCase
{
    use RefreshDatabase;

   /** @test */
    public function it_deletes_a_trip_template()
    {
        $user = factory(User::class)->create();
        $user->assignRole(Role::create(['name' => 'admin'])->name);
        Passport::actingAs($user);

        $template = factory(TripTemplate::class)->create();

        $response = $this->deleteJson("/api/campaigns/{$template->campaign_id}/trip-templates/{$template->id}");
        
        $response->assertStatus(204);

        $this->assertDatabaseMissing('trip_templates', ['id' => $template->id]);
    }
}
