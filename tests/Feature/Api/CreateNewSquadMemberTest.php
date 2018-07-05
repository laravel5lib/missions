<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\v1\Squad;
use App\Models\v1\Reservation;
use App\Models\v1\SquadMember;
use App\Notifications\SquadPublished;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateNewSquadMemberTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function create_new_squad_member()
    {
        $reservation = factory(Reservation::class)->create();
        $squad = factory(Squad::class)->create();

        $response = $this->json('POST', '/api/squad-members', [
            'reservation_ids' => $reservation->id,
            'squad_id' => $squad->uuid,
            'group' => 'test'
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('squad_members', [
            'reservation_id' => $reservation->id, 'squad_id' => $squad->id, 'group' => 'test'
        ]);
    }

    /** @test */
    public function bulk_add_squad_members()
    {
        $reservationIdsArray = factory(Reservation::class, 4)->create()->pluck('id');
        $squad = factory(Squad::class)->create();

        $response = $this->postJson('/api/squad-members', [
            'reservation_ids' => $reservationIdsArray,
            'squad_id' => $squad->uuid,
            'group' => 'test'
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('squad_members', [
            'squad_id' => $squad->id, 'group' => 'test'
        ]);

        $this->assertEquals(4, SquadMember::where('squad_id', $squad->id)->count());
    }

    /** @test */
    public function move_a_companion_from_old_squad_to_the_new_one()
    {   
        $oldSquad = factory(Squad::class)->create(['callsign' => 'Old Squad']);
        $member = factory(SquadMember::class)->create(['group' => 'Old Group', 'squad_id' => $oldSquad->id]);

        $squad = factory(Squad::class)->create(['callsign' => 'New Squad']);

        $response = $this->json('POST', '/api/squad-members', [
            'reservation_ids' => $member->reservation_id,
            'squad_id' => $squad->uuid,
            'group' => 'New Group'
        ]);

        $this->assertDatabaseMissing('squad_members', ['group' => 'Old Group', 'squad_id' => $oldSquad->id]);
        $this->assertDatabaseHas('squad_members', ['group' => 'New Group', 'squad_id' => $squad->id]);
    }

    /** @test */
    public function notify_member_when_added_to_a_published_squad()
    {
        $reservation = factory(Reservation::class)->create();
        $squad = factory(Squad::class)->create(['published' => true]);

        Notification::fake();

        $response = $this->json('POST', '/api/squad-members', [
            'reservation_ids' => $reservation->id,
            'squad_id' => $squad->uuid,
            'group' => 'test'
        ]);
        
        $response->assertStatus(201);

        Notification::assertSentTo(
            [$reservation], SquadPublished::class
        );
    }

    /** @test */
    public function does_not_notify_member_when_added_to_an_unpublished_squad()
    {
        $reservation = factory(Reservation::class)->create();
        $squad = factory(Squad::class)->create(['published' => false]);

        Notification::fake();

        $response = $this->json('POST', '/api/squad-members', [
            'reservation_ids' => $reservation->id,
            'squad_id' => $squad->uuid,
            'group' => 'test'
        ]);
        
        $response->assertStatus(201);

        Notification::assertNotSentTo(
            [$reservation], SquadPublished::class
        );
    }
}
