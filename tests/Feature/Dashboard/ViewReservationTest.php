<?php

namespace Tests\Feature\Dashboard;

use Tests\TestCase;
use App\Models\v1\User;
use App\Models\v1\Reservation;
use App\Models\v1\CampaignGroup;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewReservationTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();

        $this->reservation = factory(Reservation::class)->create();
        factory(CampaignGroup::class)->create([
            'campaign_id' => $this->reservation->trip->campaign_id, 
            'group_id' => $this->reservation->trip->group_id
        ]);
    }

    /** @test */
    public function see_squad_page_for_reservation()
    {
        $this->actingAs($this->user)
             ->get("dashboard/reservations/{$this->reservation->id}/squad")
             ->assertStatus(200);
    }
}
