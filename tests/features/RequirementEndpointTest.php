<?php

use App\Models\v1\Trip;
use App\Models\v1\Requirement;
use App\Models\v1\Reservation;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RequirementEndpointTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    /**
     * @test
     */
    public function fetches_trip_requirements_from_url()
    {
        $trip = factory(Trip::class)->create();

        $trip->requirements()->saveMany(factory(Requirement::class, 4)->make([
            'requester_id' => $trip->id
        ]));

        $this->get('/api/requirements?requester=trips|'.$trip->id)
            ->seeJsonStructure([
                'data' => [
                    '*' => [
                        'id', 'name', 'short_desc', 'document_type',
                        'grace_period', 'due_at', 'created_at', 'updated_at'
                    ]
                ]
            ]);
    }

    /**
     * @test
     */
    public function fetches_reservation_requirements_from_url()
    {
        $reservation = factory(Reservation::class)->create();

        $reservation->syncRequirements($reservation->trip->requirements);

        $this->get('/api/reservations/'. $reservation->id . '/requirements')
            ->seeJsonStructure([
                'data' => [
                    '*' => [
                        'id', 'name', 'short_desc', 'document_type',
                        'grace_period', 'due_at', 'status', 'updated_at'
                    ]
                ]
            ]);
    }
}
