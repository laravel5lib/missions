<?php

class RoomsEndpointTest extends TestCase
{
    /** @test */
    public function fetches_all_rooms()
    {
        $rooms = factory(App\Models\v1\Room::class, 3)->create();

        $this->get('api/rooms')->assertResponseOk();
    }
}
