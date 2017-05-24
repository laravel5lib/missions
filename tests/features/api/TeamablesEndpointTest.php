<?php

class TeamablesEndpointTest extends TestCase
{
    /** @test */
    public function add_teams_to_group()
    {
        $team = factory(App\Models\v1\Team::class)->create();
        $group = factory(App\Models\v1\Group::class)->create();

        $this->post('api/groups/'.$group->id.'/teams', [ 'ids' => [$team->id]])
             ->assertResponseStatus(201)
             ->seeInDatabase('teamables', [
                'team_id' => $team->id,
                'teamable_id' => $group->id,
                'teamable_type' => 'groups'
            ]);
    }

    /** @test */
    public function add_teams_to_campaign()
    {
        $team = factory(App\Models\v1\Team::class)->create();
        $campaign = factory(App\Models\v1\Campaign::class)->create();

        $this->post('api/campaigns/'.$campaign->id.'/teams', [ 'ids' => [$team->id]])
             ->assertResponseStatus(201)
             ->seeInDatabase('teamables', [
                'team_id' => $team->id,
                'teamable_id' => $campaign->id,
                'teamable_type' => 'campaigns'
            ]);
    }
}
