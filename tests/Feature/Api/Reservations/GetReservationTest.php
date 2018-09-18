<?php

namespace Tests\Feature\Api\Reservations;

use Tests\TestCase;
use App\Models\v1\Trip;
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

    /** @test */
    public function filters_reservations_by_trip_tags()
    {   
        $trip = factory(Trip::class)->create();
        $trip->syncTagsWithType(['Location: Lima', 'Peru Flight Included'], 'trip');
        $taggedReservation = factory(Reservation::class)->create(['trip_id' => $trip->id]);
        $untaggedReservation = factory(Reservation::class)->create();

        $this->assertContains('Location: Lima', $trip->tags->pluck('name')->toArray());

        $response = $this->getJson("/api/reservations?filter[trip_tags]=Location:+Lima,Peru+Flight+Included");

        $response->assertJson([
            'data' => [
                ['id' => $taggedReservation->id]
            ]
        ]);

        $response->assertJsonMissing([
            'data' => [
                ['id' => $untaggedReservation->id]
            ]
        ]);
    }
}
