<?php

namespace Tests\Feature\Dashboard\Reservations;

use Tests\TestCase;
use App\Models\v1\User;
use App\Models\v1\Requirement;
use App\Models\v1\Reservation;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewRequirementsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function requirements_view_is_loaded_with_data()
    {
        $this->actingAs(new User);
        $reservation = factory(Reservation::class)->create();

        $response = $this->get("/dashboard/reservations/{$reservation->id}/requirements");

        $response->assertOk()
                 ->assertViewIs('dashboard.reservations.requirements.index')
                 ->assertViewHasAll([
                    'tab', 'rep', 'requirements', 'reservation'
                 ]);
    }
}
