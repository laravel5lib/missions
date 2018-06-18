<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\v1\Trip;
use App\Models\v1\Reservation;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExportReservationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function exports_reservations_for_campaign()
    {
        $trip = factory(Trip::class)->create();
        factory(Reservation::class, 10)->create(['trip_id' => $trip->id]);

        Excel::fake();

        $this->get("reservations/export?filter[campaign]=$trip->campaign_id")
             ->assertStatus(302);

        Excel::assertDownloaded('reservations.xlsx');
    }
}
