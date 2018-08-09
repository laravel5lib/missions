<?php

namespace Tests\Feature\Api\Squads;

use Tests\TestCase;
use App\Models\v1\SquadMember;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RemoveSquadMemberTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function remove_multiple_members_from_multiple_squads()
    {
        $memberIdToKeep = factory(SquadMember::class)->create()->uuid;
        $memberIdsToRemove = factory(SquadMember::class, 2)->create()->pluck('uuid');

        $response = $this->deleteJson("/api/squad-members/$memberIdsToRemove[0],$memberIdsToRemove[1]");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('squad_members', ['uuid' => $memberIdsToRemove[0]]);
        $this->assertDatabaseMissing('squad_members', ['uuid' => $memberIdsToRemove[1]]);
        $this->assertDatabaseHas('squad_members', ['uuid' => $memberIdToKeep]);
    }

    /** @test */
    public function remove_member_from_squad()
    {
        $member = factory(SquadMember::class)->create();

        $response = $this->deleteJson("/api/squad-members/{$member->uuid}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('squad_members', ['id' => $member->id]);
    }
}
