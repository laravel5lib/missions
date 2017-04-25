<?php

class TeamsEndpointTest extends TestCase
{
    /** @test */
    public function fetches_all_teams()
    {
        $teams = factory(App\Models\v1\Team::class, 2)->create();

        $this->get('api/teams')
             ->assertResponseOk()
             ->seeJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'callsign',
                        'locked',
                        'created_at',
                        'updated_at',
                        'deleted_at'
                    ]
                ]
            ]);
    }

    /** @test */
    public function fetches_team_by_id()
    {
        $team = factory(App\Models\v1\Team::class)->create();

        $this->get('api/teams/' . $team->id)
             ->assertResponseOk()
             ->seeJson([
                'id' => $team->id,
                'callsign' => $team->callsign,
                'locked' => false,
                'created_at' => $team->created_at->toDateTimeString(),
                'updated_at' => $team->updated_at->toDateTimeString(),
                'deleted_at' => null
            ]);
    }

    /** @test */
    public function creates_a_new_team()
    {
        $this->post('api/teams', ['callsign' => 'Team #1'])
             ->assertResponseOk()
             ->seeInDatabase('teams', ['callsign' => 'Team #1'])
             ->seeJson([
                'callsign' => 'Team #1',
            ]);
    }

    /** @test */
    public function updates_a_team()
    {
        $team = factory(App\Models\v1\Team::class)
            ->create(['callsign' => 'Team #1']);

        $this->put('api/teams/' . $team->id, ['callsign' => 'Team #2'])
             ->assertResponseOk()
             ->seeInDatabase('teams', ['callsign' => 'Team #2'])
             ->seeJson([
                'id' => $team->id,
                'callsign' => 'Team #2'
            ]);
    }

    /** @test */
    public function deletes_a_team()
    {
        $team = factory(App\Models\v1\Team::class)
            ->create(['callsign' => 'Team #1']);

        $this->delete('api/teams/' . $team->id)
             ->assertResponseStatus(204)
             ->seeInDatabase('teams', ['id' => $team->id])
             ->dontSeeInDatabase('teams', ['id' => $team->id, 'deleted_at' => null]);
    }
}
