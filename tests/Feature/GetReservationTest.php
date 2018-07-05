<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\v1\User;
use App\Models\v1\Reservation;
use App\Models\v1\SquadMember;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetReservationTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        Passport::actingAs(factory(User::class)->create());
    }

    /** @test */
    public function get_reservations_not_assigned_to_any_squads()
    {   
        $reservation = factory(Reservation::class)->create();
        factory(SquadMember::class, 2)->create();

        $this->assertEquals(3, Reservation::count());
        
        $response = $this->getJson("/api/reservations?filter[squads_count]=0");

        $response->assertJson([
            'data' => [
                [ 'id' => $reservation->id ]
            ],
            'meta' => ['total' => 1]
        ]);
    }

    /** @test */
    public function get_reservations_assigned_to_at_least_one_squad()
    {   
        factory(Reservation::class, 2)->create();
        $member = factory(SquadMember::class)->create();

        $this->assertEquals(3, Reservation::count());
        
        $response = $this->getJson("/api/reservations?filter[squads_count]=1");

        $response->assertJson([
            'data' => [
                [ 'id' => $member->reservation_id ]
            ],
            'meta' => ['total' => 1]
        ]);
    }
}
