<?php

class TeamablesEndpointTest extends TestCase
{
    /** @test */
    public function adds_groups_to_team()
    {
        $team = factory(App\Models\v1\Team::class)->create();
        $group = factory(App\Models\v1\Group::class)->create();

        $this->post('api/teams/' . $team->id . '/groups', ['ids' => [$group->id]])
             ->assertResponseStatus(201)
             ->seeInDatabase('teamables', [
                'team_id' => $team->id,
                'teamable_id' => $group->id,
                'teamable_type' => 'groups'
            ]);
    }

    /** @test */
    public function adds_campaigns_to_team()
    {
        $team = factory(App\Models\v1\Team::class)->create();
        $campaign = factory(App\Models\v1\Campaign::class)->create();

        $this->post('api/teams/' . $team->id . '/campaigns', ['ids' => [$campaign->id]])
             ->assertResponseStatus(201)
             ->seeInDatabase('teamables', [
                'team_id' => $team->id,
                'teamable_id' => $campaign->id,
                'teamable_type' => 'campaigns'
            ]);
    }

    /** @test */
    public function removes_groups_from_team()
    {
        $team = factory(App\Models\v1\Team::class)->create();
        $group = factory(App\Models\v1\Group::class)->create();

        $this->delete('api/teams/' . $team->id . '/groups', ['ids' => [$group->id]])
             ->assertResponseStatus(204)
             ->dontSeeInDatabase('teamables', [
                'team_id' => $team->id,
                'teamable_id' => $group->id,
                'teamable_type' => 'groups'
            ]);
    }

    /** @test */
    public function removes_campaigns_from_team()
    {
        $team = factory(App\Models\v1\Team::class)->create();
        $campaign = factory(App\Models\v1\Campaign::class)->create();

        $this->delete('api/teams/' . $team->id . '/campaigns', ['ids' => [$campaign->id]])
             ->assertResponseStatus(204)
             ->dontSeeInDatabase('teamables', [
                'team_id' => $team->id,
                'teamable_id' => $campaign->id,
                'teamable_type' => 'campaigns'
            ]);
    }
}
