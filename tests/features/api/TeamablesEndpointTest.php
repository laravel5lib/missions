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

    /** @test */
    public function add_teams_to_region()
    {
        $type = factory(App\Models\v1\TeamType::class)->create();
        $team = factory(App\Models\v1\Team::class)->create(['type_id' => $type->id]);
        $campaign = factory(App\Models\v1\Campaign::class)->create();
        $region = factory(App\Models\v1\Region::class)->create(['campaign_id' => $campaign->id]);

        $this->post('api/regions/'.$region->id.'/teams', [ 'ids' => [$team->id]])
             ->assertResponseStatus(201)
             ->seeInDatabase('teamables', [
                'team_id' => $team->id,
                'teamable_id' => $region->id,
                'teamable_type' => 'regions'
             ]);
    }

    /** @test */
    public function remove_team_from_group()
    {
        $team = factory(App\Models\v1\Team::class)->create();
        $group = factory(App\Models\v1\Group::class)->create();
        $group->teams()->attach($team->id);

        $this->delete('api/groups/'.$group->id.'/teams/'.$team->id)
             ->assertResponseStatus(204)
             ->dontSeeInDatabase('teamables', [
                'team_id' => $team->id,
                'teamable_id' => $group->id,
                'teamable_type' => 'groups'
             ]);
    }

    /** @test */
    public function remove_team_from_campaign()
    {
        $team = factory(App\Models\v1\Team::class)->create();
        $campaign = factory(App\Models\v1\Campaign::class)->create();
        $campaign->teams()->attach($team->id);

        $this->delete('api/campaigns/'.$campaign->id.'/teams/'.$team->id)
             ->assertResponseStatus(204)
             ->dontSeeInDatabase('teamables', [
                'team_id' => $team->id,
                'teamable_id' => $campaign->id,
                'teamable_type' => 'campaigns'
             ]);
    }

    /** @test */
    public function remove_team_from_region()
    {
        $type = factory(App\Models\v1\TeamType::class)->create();
        $team = factory(App\Models\v1\Team::class)->create(['type_id' => $type->id]);
        $campaign = factory(App\Models\v1\Campaign::class)->create();
        $region = factory(App\Models\v1\Region::class)->create(['campaign_id' => $campaign->id]);
        $region->teams()->attach($team->id);

        $this->delete('api/regions/'.$region->id.'/teams/'.$team->id)
             ->assertResponseStatus(204)
             ->dontSeeInDatabase('teamables', [
                'team_id' => $team->id,
                'teamable_id' => $region->id,
                'teamable_type' => 'regions'
             ]);
    }
}
