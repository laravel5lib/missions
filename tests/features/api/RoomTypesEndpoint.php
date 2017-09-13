<?php

use App\Models\v1\Campaign;

class RoomTypesEndpoint extends BrowserKitTestCase
{
    use AuthenticatedUserSetup;

    /** @test */
    public function fetches_all_room_types()
    {
        $types = factory(App\Models\v1\RoomType::class, 3)->create();

        $this->get('api/rooming/types')
             ->assertResponseOk()
             ->seeJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'campaign_id',
                        'name',
                        'rules',
                        'created_at',
                        'updated_at',
                        'deleted_at'
                    ]
                ]
             ]);
    }

    /** @test */
    public function fetches_room_type_by_id()
    {
        $type = factory(App\Models\v1\RoomType::class)->create();

        $this->get('api/rooming/types/' . $type->id)
             ->assertResponseOk()
             ->seeJson(['id' => $type->id, 'name' => $type->name]);
    }

    /** @test */
    public function creates_new_room_type()
    {
        $campaign = factory(Campaign::class)->create();

        $type = [
            'name' => 'Double',
            'rules' => ['occupancy_limit' => 2],
            'campaign_id' => $campaign->id
        ];

        $this->post('api/rooming/types', $type)
             ->assertResponseOk()
             ->seeJson(['name' => 'Double', 'campaign_id' => $campaign->id]);
    }

    /** @test */
    public function updates_a_room_type()
    {
        $type = factory(App\Models\v1\RoomType::class)->create([
            'name' => 'Double'
        ]);

        $update = ['name' => 'Triple'];

        $this->put('api/rooming/types/' . $type->id, $update)
             ->assertResponseOk()
             ->seeJson(['name' => 'Triple']);
    }

    /** @test */
    public function deletes_a_room_type()
    {
        $type = factory(App\Models\v1\RoomType::class)->create();

        $this->delete('api/rooming/types/' . $type->id)
             ->dontSeeInDatabase('room_types', ['id' => $type->id, 'deleted_at' => null])
             ->assertResponseStatus(204);
    }
}
