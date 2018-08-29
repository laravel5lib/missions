<?php

namespace Tests\Feature\Api\Questionnaires;

use Tests\TestCase;
use App\Models\v1\Requirement;
use App\Models\v1\Reservation;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateQuestionnaireTest extends TestCase
{
    use RefreshDatabase;

    protected $reservation;

    public function setUp()
    {
        parent::setUp();
        $this->reservation = factory(Reservation::class)->create();
    }

    /** @test */
    public function create_new_airport_preference_and_update_requirement_status()
    {
        $requirement = factory(Requirement::class)
                                ->create([
                                    'document_type' => 'airport_preferences',
                                    'requester_type' => 'reservations', 
                                    'requester_id' => $this->reservation->id
                                ]);
        $this->reservation->addRequirement(['requirement_id' => $requirement->id]);

        $response = $this->postJson('/api/airport-preferences', [
            'content' => ['AUS', 'DFW', 'IAH'], 
            'reservation_id' => $this->reservation->id
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas(
            'questionnaires', 
            [
                'type' => 'airport_preference', 
                'reservation_id' => $this->reservation->id, 
                'content' => castToJson(['AUS', 'DFW', 'IAH'])
            ]
        );

        $this->assertDatabaseHas(
            'requireables', 
            [
                'requirement_id' => $requirement->id, 
                'requireable_type' => 'reservations', 
                'requireable_id' => $this->reservation->id, 
                'status' => 'reviewing'
            ]
        );
    }

    /** @test */
    public function create_new_arrival_designation()
    {
        $response = $this->postJson('/api/arrival-designations', [
            'content' => ['eastern'], 
            'reservation_id' => $this->reservation->id
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas(
            'questionnaires', 
            [
                'type' => 'arrival_designation', 
                'reservation_id' => $this->reservation->id, 
                'content' => castToJson(['eastern'])
            ]
        );
    }

    /** @test */
    public function create_new_minor_release()
    {
        $response = $this->postJson('/api/minor-releases', [
            'content' => ['name' => 'Jack Smith'],
            'reservation_id' => $this->reservation->id
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas(
            'questionnaires', 
            [
                'type' => 'minor_release', 
                'reservation_id' => $this->reservation->id,
                'content' => castToJson(['name' => 'Jack Smith'])
            ]
        );
    }
}
