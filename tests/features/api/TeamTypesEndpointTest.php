<?php

class TeamTypesEndpointTest extends BrowserKitTestCase
{
    /** @test */
    public function fetches_all_team_types()
    {
        factory(App\Models\v1\TeamType::class, 2)->create();

        $this->get('api/teams/types')
             ->assertResponseOk()
             ->seeJsonStructure([
                'data' => [
                    '*' => [
                        'id', 'name', 'rules', 'created_at', 'updated_at'
                    ]
                ]
             ]);
    }

    /** @test */
    public function fetches_team_type_by_id()
    {
        $type = factory(App\Models\v1\TeamType::class)->create();

        $this->get('api/teams/types')
             ->assertResponseOk()
             ->seeJson([
                'id' => $type->id,
                'name' => $type->name,
                'rules' => $type->rules,
                'created_at' => $type->created_at->toDateTimeString(),
                'updated_at' => $type->updated_at->toDateTimeString()
             ]);
    }

    /** @test */
    public function creates_team_type()
    {
        $campaign = factory(App\Models\v1\Campaign::class)->create();

        $type = [ 'name' => 'Medical', 'rules' => ['max_members' => 25], 'campaign_id' => $campaign->id ];

        $this->post('api/teams/types', $type)
             ->assertResponseOk()
             ->seeJson([
                'name' => 'medical',
                'rules' => [
                    'max_members' => 25
                ]
             ]);
    }

    /** @test */
    public function updates_team_type()
    {
        $type = factory(App\Models\v1\TeamType::class)->create([
            'rules' => ['max_members' => 25, 'min_members' => 10]
        ]);

        $changes = [ 'rules' => ['max_members' => 15 ]];

        $this->put('api/teams/types/' . $type->id, $changes)
             ->assertResponseOk()
             ->seeJson([
                'rules' => [
                    'max_members' => 15,
                    'min_members' => 10
                ]
             ]);
    }

    /** @test */
    public function deletes_team_type()
    {
        $type = factory(App\Models\v1\TeamType::class)->create();

        $this->delete('api/teams/types/' . $type->id)
             ->assertResponseStatus(204)
             ->dontSeeInDatabase('team_types', ['id' => $type->id, 'deleted_at' => 'null']);
    }
}
