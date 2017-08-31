<?php

use Carbon\Carbon;
use App\Models\v1\Fund;
use App\Models\v1\Trip;
use App\Models\v1\Reservation;

class ReservationsEndpointTest extends TestCase
{

    /** @test */
    public function creates_reservation()
    {
        $reservation = factory(Reservation::class)->make([
            'given_names' => 'John Doe',
            'weight'      => 160,
            'height_a'    => 5,
            'height_b'    => 0
        ]);

        $this->post('/api/reservations', $reservation->toArray())
             ->assertResponseOk()
             ->seeJson(['given_names' => 'John Doe']);
    }

    /** @test */
    public function updates_reservation()
    {
        $reservation = factory(Reservation::class)->create([
                'given_names' => 'John Smith'
            ]);

        $reservation['given_names'] = 'Jimmy John';

        $this->put('/api/reservations/'.$reservation->id, $reservation->toArray())
             ->assertResponseOk()
             ->seeJson(['given_names' => 'Jimmy John']);
    }

    /** @test */
    public function soft_deletes_a_reservation()
    {
        $reservation = factory(Reservation::class)->create();

        $this->delete('/api/reservations/'.$reservation->id)
             ->assertResponseStatus(204)
             ->dontSeeInDatabase('reservations', ['deleted_at' => null]);
    }

    /** @test */
    public function returns_soft_deleted_reservations()
    {
        $reservation = factory(Reservation::class)->create([
            'deleted_at' => Carbon::now()
        ]);

        $this->get('/api/reservations?dropped=true')
             ->assertResponseOk()
             ->seeJson([ 'id' => $reservation->id ]);
    }

    /** @test */
    public function restores_a_soft_deleted_reservation()
    {
        $reservation = factory(Reservation::class)->create([
            'deleted_at' => Carbon::now()
        ]);

        $this->put('/api/reservations/'.$reservation->id.'/restore')
             ->assertResponseOk()
             ->seeInDatabase('reservations', ['deleted_at' => null]);
    }

    /** @test */
    public function transfers_reservation_to_another_trip()
    {
        $reservation = factory(Reservation::class)->create();
        $fund = factory(Fund::class)->create([
            'fundable_id' => $reservation->id,
            'fundable_type' => 'reservations'
        ]);
        $trip = factory(Trip::class)->create();

        $this->post(
            '/api/reservations/'.$reservation->id.'/transfer',
            ['trip_id' => $trip->id, 'desired_role' => 'MISS']
        )
        ->assertResponseOk()
        ->seeInDatabase('reservations', ['trip_id' => $trip->id, 'desired_role' => 'MISS']);
    }
}
