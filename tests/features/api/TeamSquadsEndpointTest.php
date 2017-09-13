<?php

class TeamSquadsEndpointTest extends BrowserKitTestCase
{
    use AuthenticatedUserSetup;

    /** @test */
    public function fetches_all_squads_in_team()
    {
        $team = factory(App\Models\v1\Team::class)->create();
        $squads = factory(App\Models\v1\TeamSquad::class, 2)->create([
            'team_id' => $team->id
        ]);

        $this->get('api/teams/' . $team->id . '/squads')
             ->assertResponseOk()
             ->seeJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'team_id',
                        'callsign'
                    ]
                ]
             ]);
    }

    /** @test */
    public function fetch_team_squad_by_id()
    {
        $team = factory(App\Models\v1\Team::class)->create();
        $squad = factory(App\Models\v1\TeamSquad::class)->create([
            'team_id' => $team->id
        ]);

        $this->get('api/teams/' . $team->id . '/squads/' . $squad->id)
             ->assertResponseOk()
             ->seeJson([
                'id' => $squad->id,
                'callsign' => $squad->callsign
             ]);
    }

    /** @test */
    public function adds_squad_to_team()
    {
        $type = factory(App\Models\v1\TeamType::class)->create();
        $team = factory(App\Models\v1\Team::class)->create(['type_id' => $type->id]);

        $this->post('api/teams/' . $team->id . '/squads', ['callsign' => 'Group #1'])
             ->assertResponseOk()
             ->seeInDatabase('team_squads', ['team_id' => $team->id, 'callsign' => 'Group #1'])
             ->seeJson([
                'team_id'  => $team->id,
                'callsign' => 'Group #1'
             ]);
    }

    /** @test */
    public function updates_squad_on_team()
    {
        $team = factory(App\Models\v1\Team::class)->create();
        $squad = factory(App\Models\v1\TeamSquad::class)
            ->create(['team_id' => $team->id, 'callsign' => 'Group #1']);

        $this->put('api/teams/' . $team->id . '/squads/' . $squad->id, ['callsign' => 'Group #2'])
             ->assertResponseOk()
             ->seeInDatabase('team_squads', ['team_id' => $team->id, 'callsign' => 'Group #2'])
             ->seeJson([
                'team_id'  => $team->id,
                'callsign' => 'Group #2'
             ]);
    }

    /** @test */
    public function removes_squad_from_team()
    {
        $type = factory(App\Models\v1\TeamType::class)->create();
        $team = factory(App\Models\v1\Team::class)->create(['type_id' => $type->id]);
        $squad = factory(App\Models\v1\TeamSquad::class)
            ->create(['team_id' => $team->id]);
        $squadTwo = factory(App\Models\v1\TeamSquad::class)
            ->create(['team_id' => $team->id]);

        $this->delete('api/teams/' . $team->id . '/squads/' . $squad->id)
             ->assertResponseStatus(204)
             ->dontSeeInDatabase('team_squads', ['id' => $squad->id, 'team_id' => $team->id]);
    }
}
