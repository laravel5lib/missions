<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\v1\Trip;
use App\Models\v1\Group;
use App\Models\v1\Squad;
use App\Models\v1\Region;
use App\Models\v1\Campaign;
use App\Models\v1\Reservation;
use App\Models\v1\SquadMember;
use Tests\Feature\SquadMemberTest;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetSquadMemberTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function get_all_members_in_squad()
    {
        $squad = factory(Squad::class)->create();
        factory(SquadMember::class, 2)->create(['squad_id' => $squad->id]);
        factory(SquadMember::class)->create();

        $this->json('GET', "/api/squad-members?filter[squad_id]={$squad->uuid}")
             ->assertStatus(200)
             ->assertJsonStructure([
                 'data' => [
                    [
                        'id', 
                        'surname', 
                        'given_names',
                        'gender',
                        'status',
                        'birthday',
                        'age',
                        'role' => ['code', 'name'],
                        'squad_callsign',
                        'squad_group',
                        'region_name',
                        'organization_name',
                        'trip_type',
                        'created_at',
                        'updated_at'
                    ]
                ],
                'meta'
             ])
             ->assertJson(['meta' => ['total' => 2]]);
    }

    /** @test */
    public function get_all_members_for_campaign()
    {   
        $campaign = factory(Campaign::class)->create();
        $region = factory(Region::class)->create(['campaign_id' => $campaign->id]);
        $squad = factory(Squad::class)->create(['region_id' => $region->id]);
        factory(SquadMember::class, 2)->create(['squad_id' => $squad->id]);
        factory(SquadMember::class)->create();

        $this->assertEquals(SquadMember::count(), 3);

        $response = $this->json('GET', "/api/squad-members?filter[campaign_id]={$region->campaign_id}");
             
        $response->assertStatus(200)->assertJson(['meta' => ['total' => 2]]);
    }

    /** @test */
    public function search_members_by_given_names()
    {
        $reservation = factory(Reservation::class)
            ->create(['given_names' => 'George Washington', 'surname' => 'Carver']);

        factory(SquadMember::class)->create(['reservation_id' => $reservation->id]);
        factory(SquadMember::class)->create();

        $response = $this->json('GET', "/api/squad-members?filter[given_names]=George+Washington");

        $response->assertStatus(200)->assertJson([
            'data' => [
                [
                    'given_names' => 'George Washington',
                    'surname' => 'Carver'
                ]
            ]
        ]);
    }

    /** @test */
    public function search_members_by_surname()
    {
        $reservation = factory(Reservation::class)
            ->create(['given_names' => 'George Washington', 'surname' => 'Carver']);

        factory(SquadMember::class)->create(['reservation_id' => $reservation->id]);
        factory(SquadMember::class)->create();

        $response = $this->json('GET', "/api/squad-members?filter[surname]=Carver");

        $response->assertStatus(200)->assertJson([
            'data' => [
                [
                    'given_names' => 'George Washington',
                    'surname' => 'Carver'
                ]
            ]
        ]);
    }

    /** @test */
    public function get_members_by_gender()
    {
        $maleMissionary = factory(Reservation::class)->create(['gender' => 'male']);
        $femaleMissionary = factory(Reservation::class)->create(['gender' => 'female']);
        factory(SquadMember::class)->create(['reservation_id' => $maleMissionary->id]);
        factory(SquadMember::class)->create(['reservation_id' => $femaleMissionary->id]);

        $response = $this->json('GET', "/api/squad-members?filter[gender]=male");

        $response->assertStatus(200)->assertJson([
            'data' => [
                [
                    'gender' => 'male',
                ]
            ],
            'meta' => ['total' => 1]
        ]);
    }

    /** @test */
    public function get_members_by_status()
    {
        $marriedMissionary = factory(Reservation::class)->create(['status' => 'married']);
        $singleMissionary = factory(Reservation::class)->create(['status' => 'single']);
        factory(SquadMember::class)->create(['reservation_id' => $marriedMissionary->id]);
        factory(SquadMember::class)->create(['reservation_id' => $singleMissionary->id]);

        $response = $this->json('GET', "/api/squad-members?filter[status]=married");

        $response->assertStatus(200)->assertJson([
            'data' => [
                [
                    'status' => 'married',
                ]
            ],
            'meta' => ['total' => 1]
        ]);
    }

    /** @test */
    public function get_members_by_role()
    {
        $missionary = factory(Reservation::class)->create(['desired_role' => 'MISS']);
        $media = factory(Reservation::class)->create(['desired_role' => 'MEDI']);
        factory(SquadMember::class)->create(['reservation_id' => $missionary->id]);
        factory(SquadMember::class)->create(['reservation_id' => $media->id]);

        $response = $this->json('GET', "/api/squad-members?filter[role]=MISS");

        $response->assertStatus(200)->assertJson([
            'data' => [
                [
                    'role' => [
                        'code' => 'MISS',
                        'name' => 'Missionary (13+)'
                    ]
                ]
            ],
            'meta' => ['total' => 1]
        ]);
    }

    /** @test */
    public function get_members_by_group()
    {
        factory(SquadMember::class)->create(['group' => 'alpha']);
        factory(SquadMember::class)->create(['group' => 'bravo']);

        $response = $this->json('GET', "/api/squad-members?filter[group]=alpha");

        $response->assertStatus(200)->assertJson([
            'data' => [
                [
                    'squad_group' => 'alpha',
                ]
            ],
            'meta' => ['total' => 1]
        ]);
    }

    /** @test */
    public function get_members_by_squad_callsign()
    {
        $seals = factory(Squad::class)->create(['callsign' => 'Seal Team Six']);
        $delta = factory(Squad::class)->create(['callsign' => 'Delta Force']);
        factory(SquadMember::class)->create(['squad_id' => $seals->id]);
        factory(SquadMember::class)->create(['squad_id' => $delta->id]);

        $response = $this->json('GET', "/api/squad-members?filter[squad_callsign]=Seal+Team");

        $response->assertStatus(200)->assertJson([
            'data' => [
                [
                    'squad_callsign' => 'Seal Team Six',
                ]
            ],
            'meta' => ['total' => 1]
        ]);
    }

    /** @test */
    public function get_members_by_region()
    {
        $region = factory(Region::class)->create(['name' => 'Lima']);
        $squad = factory(Squad::class)->create(['region_id' => $region->id]);
        $member = factory(SquadMember::class)->create(['squad_id' => $squad->id]);
        factory(SquadMember::class)->create();

        $response = $this->json('GET', "/api/squad-members?filter[region_id]=$region->id");

        $response->assertStatus(200)->assertJson([
            'data' => [
                [
                    'id' => $member->uuid,
                    'region_name' => 'Lima',
                ]
            ],
            'meta' => ['total' => 1]
        ]);
    }

    /** @test */
    public function get_members_by_trip_type()
    {
        $ministryTrip = factory(Trip::class)->create(['type' => 'ministry']);
        $ministryReservation = factory(Reservation::class)->create(['trip_id' => $ministryTrip->id]);
        factory(SquadMember::class)->create(['reservation_id' => $ministryReservation->id]);

        $medicalTrip = factory(Trip::class)->create(['type' => 'medical']);
        $medicalReservation = factory(Reservation::class)->create(['trip_id' => $medicalTrip->id]);
        factory(SquadMember::class)->create(['reservation_id' => $medicalReservation->id]);

        $response = $this->json('GET', "/api/squad-members?filter[trip_type]=ministry");

        $response->assertStatus(200)->assertJson([
            'data' => [
                [
                    'trip_type' => 'ministry',
                ]
            ],
            'meta' => ['total' => 1]
        ]);
    }

    /** @test */
    public function get_members_by_organization_name()
    {
        $group = factory(Group::class)->create(['name' => 'Victory Church']);
        $trip = factory(Trip::class)->create(['group_id' => $group->id]);
        $reservation = factory(Reservation::class)->create(['trip_id' => $trip->id]);
        factory(SquadMember::class)->create(['reservation_id' => $reservation->id]);
        factory(SquadMember::class)->create();

        $response = $this->json('GET', "/api/squad-members?filter[organization_name]=Victory");

        $response->assertStatus(200)->assertJson([
            'data' => [
                [
                    'organization_name' => 'Victory Church',
                ]
            ],
            'meta' => ['total' => 1]
        ]);
    }

    /** @test */
    public function get_members_by_age_range()
    {
        $otherAge = factory(Reservation::class)->create(['birthday' => '2000-01-05']);
        factory(SquadMember::class)->create(['reservation_id' => $otherAge->id]);

        $targetAge = factory(Reservation::class)->create(['birthday' => '1987-07-28']);
        $member = factory(SquadMember::class)->create(['reservation_id' => $targetAge->id]);

        $response = $this->json('GET', "/api/squad-members?filter[age_range]=25,50");

        $response->assertStatus(200)->assertJson([
            'data' => [
                [
                    'id' => $member->uuid,
                ]
            ],
            'meta' => ['total' => 1]
        ]);
    }

    /** @test */
    public function get_member_by_id()
    {
        $member = factory(SquadMember::class)->create();

        $response = $this->json('GET', "/api/squad-members/{$member->uuid}");

        $response->assertStatus(200)->assertJson([
            'data' => ['id' => $member->uuid]
        ]);
    }
}
