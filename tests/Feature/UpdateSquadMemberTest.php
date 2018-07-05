<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\v1\Squad;
use App\Models\v1\SquadMember;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateSquadMemberTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function updates_a_squad_member()
    {
        $member = factory(SquadMember::class)->create(['group' => 'Group 1']);

        $response = $this->putJson("/api/squad-members/{$member->uuid}", ['group' => 'Group 2']);

        $response->assertStatus(200);

        $this->assertDatabaseHas('squad_members', [
            'id' => $member->id, 'squad_id' => $member->squad_id, 'group' => 'Group 2'
        ]);
    }

    /** @test */
    public function bulk_updates_squad_members()
    {
        $squad = factory(Squad::class)->create();
        $squad->members()->saveMany([
            factory(SquadMember::class)->create(['group' => 'Group 1']),
            factory(SquadMember::class)->create(['group' => 'Group 2']),
            factory(SquadMember::class)->create(['group' => 'Group 3']),
            factory(SquadMember::class)->create(['group' => 'Group 4']),
        ]);

        $uuids = implode(',', $squad->members->pluck('uuid')->toArray());

        $response = $this->putJson("/api/squad-members/{$uuids}", ['group' => 'Group 5']);

        $response->assertStatus(200);

        $this->assertDatabaseHas('squad_members', ['squad_id' => $squad->id, 'group' => 'Group 5']);
        $this->assertDatabaseMissing('squad_members', ['group' => 'Group 1']);
        $this->assertDatabaseMissing('squad_members', ['group' => 'Group 2']);
        $this->assertDatabaseMissing('squad_members', ['group' => 'Group 3']);
        $this->assertDatabaseMissing('squad_members', ['group' => 'Group 4']);
    }
}
