<?php

class SquadMembersEndpointTest extends BrowserKitTestCase
{
    use AuthenticatedUserSetup;

    /** @test */
    public function fetches_all_squad_members()
    {
        $type = factory(App\Models\v1\TeamType::class)->create();
        $team = factory(App\Models\v1\Team::class)->create(['type_id' => $type->id]);
        $squad = factory(App\Models\v1\TeamSquad::class)->create(['team_id' => $team->id]);

        $squad->members()->saveMany([
            factory(App\Models\v1\Reservation::class)->make(),
            factory(App\Models\v1\Reservation::class)->make()
        ]);

        $this->get('api/squads/' . $squad->id . '/members')
             ->assertResponseOk()
             ->seeJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'given_names',
                        'surname',
                        'gender',
                        'status',
                        'age',
                        'leader',
                        'created_at',
                        'updated_at'
                    ]
                ]
             ]);
    }

    /** @test */
    public function fetches_squad_member_by_id()
    {
        $type = factory(App\Models\v1\TeamType::class)->create();
        $team = factory(App\Models\v1\Team::class)->create(['type_id' => $type->id]);
        $squad = factory(App\Models\v1\TeamSquad::class)->create(['team_id' => $team->id]);

        $member = $squad->members()->save(factory(App\Models\v1\Reservation::class)->make());

        $this->get('api/squads/' . $squad->id . '/members/' . $member->id)
             ->assertResponseOk()
             ->seeJson(['id' => $member->id]);
    }

    /** @test */
    public function adds_members_to_squad()
    {
        $type = factory(App\Models\v1\TeamType::class)->create();
        $team = factory(App\Models\v1\Team::class)->create(['type_id' => $type->id]);
        $squad = factory(App\Models\v1\TeamSquad::class)->create(['team_id' => $team->id]);
        $reservation = factory(App\Models\v1\Reservation::class)->create();

        $this->post('api/squads/' . $squad->id . '/members', ['id' => $reservation->id])
             ->assertResponseOk()
             ->seeInDatabase('team_members', [
                'team_squad_id' => $squad->id,
                'reservation_id' => $reservation->id,
                'leader' => false
             ])
             ->seeJson(['id' => $reservation->id]);
    }

    /** @test */
    public function updates_member_in_squad()
    {
        $type = factory(App\Models\v1\TeamType::class)->create();
        $team = factory(App\Models\v1\Team::class)->create(['type_id' => $type->id]);
        $squad = factory(App\Models\v1\TeamSquad::class)->create(['team_id' => $team->id]);
        $reservation = factory(App\Models\v1\Reservation::class)->create();
        $squad->members()->attach($reservation->id);

        $this->put('api/squads/' . $squad->id . '/members/' . $reservation->id, ['leader' => true])
             ->assertResponseOk()
             ->seeInDatabase('team_members', [
                'team_squad_id' => $squad->id,
                'reservation_id' => $reservation->id,
                'leader' => true
             ])
             ->seeJson(['id' => $reservation->id, 'leader' => true]);
    }

    /** @test */
    public function removes_member_from_squad()
    {
        $type = factory(App\Models\v1\TeamType::class)->create();
        $team = factory(App\Models\v1\Team::class)->create(['type_id' => $type->id]);
        $squad = factory(App\Models\v1\TeamSquad::class)->create(['team_id' => $team->id]);
        $reservation = factory(App\Models\v1\Reservation::class)->create();
        $squad->members()->attach($reservation->id);

        $this->delete('api/squads/' . $squad->id . '/members/' . $reservation->id)
             ->assertResponseStatus(204)
             ->dontSeeInDatabase('team_members', [
                'team_squad_id' => $squad->id,
                'reservation_id' => $reservation->id,
                'leader' => true
             ]);
    }
}
