<?php

class RoomTypesEndpoint extends BrowserKitTestCase
{
    /** @test */
    public function fetches_all_room_types()
    {
        $types = factory(App\Models\v1\RoomType::class, 3)->create();

        $this->get('api/rooms/types')
             ->assertResponseOk()
             ->seeJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'occupancy',
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

        $this->get('api/rooms/types/' . $type->id)
             ->assertResponseOk()
             ->seeJson(['id' => $type->id, 'name' => $type->name, 'occupancy' => $type->occupancy]);
    }

    /** @test */
    public function creates_new_room_type()
    {
        $type = ['name' => 'Double', 'occupancy' => 2];

        $this->post('api/rooms/types', $type)
             ->assertResponseOk()
             ->seeInDatabase('room_types', $type)
             ->seeJson(['name' => 'Double']);
    }

    /** @test */
    public function updates_a_room_type()
    {
        $type = factory(App\Models\v1\RoomType::class)->create([
            'name' => 'Double', 'occupancy' => 2
        ]);

        $update = ['name' => 'Triple', 'occupancy' => 3];

        $this->put('api/rooms/types/' . $type->id, $update)
             ->assertResponseOk()
             ->seeInDatabase('room_types', $update)
             ->seeJson(['name' => 'Triple', 'occupancy' => 3]);
    }

    /** @test */
    public function deletes_a_room_type()
    {
        $type = factory(App\Models\v1\RoomType::class)->create();

        $this->delete('api/rooms/types/' . $type->id)
             ->dontSeeInDatabase('room_types', ['id' => $type->id, 'deleted_at' => null])
             ->assertResponseStatus(204);
    }
}
