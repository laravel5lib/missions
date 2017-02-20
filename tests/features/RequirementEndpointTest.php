<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RequirementEndpointTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function fetches_trip_requirements_from_url()
    {
        $trip = factory(\App\Models\v1\Trip::class)->create();

        $trip->requirements()->saveMany(factory(\App\Models\v1\Requirement::class, 4)->make());

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
        $reservation = factory(\App\Models\v1\Reservation::class)->create();

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
