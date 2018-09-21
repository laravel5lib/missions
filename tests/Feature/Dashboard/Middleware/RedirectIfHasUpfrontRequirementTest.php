<?php

namespace Tests\Feature\Dashboard\Middleware;

use Tests\TestCase;
use App\Models\v1\User;
use App\Models\v1\Requirement;
use App\Models\v1\Reservation;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RedirectIfHasUpfrontRequirementTest extends TestCase
{
    /** @test */
    public function redirects_user_to_requirement_if_reservation_has_an_upfront_requirement()
    {
        $user = factory(User::class)->create();

        $reservation = factory(Reservation::class)->create(['user_id' => $user->id]);
        $requirement = factory(Requirement::class)->create([
            'upfront' => true,
            'requester_type' => 'reservations',
            'requester_id' => $reservation->id
        ]);
        $reservation->attachRequirementToModel($requirement->id);

        $response = $this->actingAs($user)->get("/dashboard/reservations/{$reservation->id}");

        $response->assertRedirect("/dashboard/reservations/{$reservation->id}/requirements/{$requirement->id}");
    }
}
