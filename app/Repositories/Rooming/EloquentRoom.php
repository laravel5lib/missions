<?php

namespace App\Repositories\Rooming;

use App\Models\v1\Room as RoomModel;
use App\Repositories\EloquentRepository;
use App\Repositories\Rooming\Interfaces\Room;

class EloquentRoom extends EloquentRepository implements Room {

    protected $model;

    protected $attributes = ['label', 'room_type_id'];

    function __construct(RoomModel $room)
    {
        $this->model = $room;
    }

    public function addOccupants($id, $occupants)
    {
        $room = $this->getById($id);

        $room->occupants()->attach($occupants);

        return true;
    }

    public function removeOccupants($id, $occupants)
    {
        $room = $this->getById($id);

        $room->occupants()->detach($occupants);

        return false;
    }
}