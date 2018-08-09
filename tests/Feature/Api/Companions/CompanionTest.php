<?php

namespace Tests\Feature\Api\Companions;

use Tests\TestCase;
use App\Models\v1\Companion;
use App\Models\v1\SquadMember;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CompanionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function get_companions_by_reservation_ids()
    {
        $companionOne = factory(Companion::class)->create();
        $companionTwo = factory(Companion::class)->create();
        $companionThree = factory(Companion::class)->create();

        // /api/companions?filter[reservation_id]=${ids}&filter[squad_id]=${squadId}

        $response = $this->getJson("/api/companions?filter[reservation_id]=$companionOne->reservation_id,$companionTwo->reservation_id");

        $response->assertJson([
            'data' => [
                [ 'id' => $companionOne->companion_id ]
            ]
        ])->assertJson([
            'data' => [
                [ 'id' => $companionTwo->companion_id ]
            ]
        ])->assertJsonMissing([
            'data' => [
                [ 'id' => $companionThree->companion_id ]
            ]
        ]);
    }

    /** @test */
    public function get_companions_not_in_a_specific_squad()
    {
        $companionOne = factory(Companion::class)->create();
        $companionTwo = factory(Companion::class)->create();
        $companionThree = factory(Companion::class)->create();
        $member = factory(SquadMember::class)->create(['reservation_id' => $companionThree->companion_id]);

        $response = $this->getJson("/api/companions?filter[not_in_squad]={$member->squad->uuid}");

        $response->assertJson([
            'data' => [
                [ 'id' => $companionTwo->companion_id ]
            ]
        ])->assertJson([
            'data' => [
                [ 'id' => $companionOne->companion_id ]
            ]
        ])->assertJsonMissing([
            'data' => [
                [ 'id' => $companionThree->companion_id ]
            ]
        ]);
    }
}
