<?php

class RoomsEndpointTest extends BrowserKitTestCase
{
    /** @test */
    public function fetches_all_rooms()
    {
        $rooms = factory(App\Models\v1\Room::class, 3)->create();

        $this->get('api/rooming/rooms')
             ->assertResponseOk()
             ->seeJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'label',
                        'created_at',
                        'updated_at',
                        'deleted_at'
                    ]
                ]
             ]);
    }

    /** @test */
    public function fetches_room_by_id()
    {
        $room = factory(App\Models\v1\Room::class)->create();

        $this->get('api/rooming/rooms/' . $room->id)
             ->assertResponseOk()
             ->seeJson(['id' => $room->id, 'label' => $room->label]);
    }

    /** @test */
    public function creates_new_room()
    {
        $type = factory(App\Models\v1\RoomType::class)->create(['name' => 'Double']);

        $room = ['label' => 'Test Room', 'room_type_id' => $type->id];

        $this->post('api/rooming/rooms', $room)
             ->assertResponseOk()
             ->seeInDatabase('rooms', $room)
             ->seeJson(['label' => 'Test Room']);
    }

    /** @test */
    public function updates_a_room()
    {
        $room = factory(App\Models\v1\Room::class)->create(['label' => 'Test Room']);

        $update = ['label' => 'Updated Room'];

        $this->put('api/rooming/rooms/' . $room->id, $update)
             ->assertResponseOk()
             ->seeInDatabase('rooms', $update)
             ->seeJson(['label' => 'Updated Room']);
    }

    /** @test */
    public function deletes_a_room()
    {
        $room = factory(App\Models\v1\Room::class)->create();

        $this->delete('api/rooming/rooms/' . $room->id)
             ->dontSeeInDatabase('rooms', ['id' => $room->id, 'deleted_at' => null])
             ->assertResponseStatus(204);
    }
}
