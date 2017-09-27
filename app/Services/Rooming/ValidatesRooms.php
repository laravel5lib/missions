<?php

namespace App\Services\Rooming;

use App\Models\v1\Room;

class ValidatesRooms
{
    protected $roomIds;
    protected $model;

    function __construct($roomIds, $model)
    {
        $this->roomIds = $roomIds;
        $this->model = $model;
    }

    /**
     * Run all assertions.
     */
    public function validate()
    {
        $this->getRooms()->each(function ($room) {
            $type = $this->model->availableRoomTypes()->where('id', $room->room_type_id)->first();

            $this->assertAllowsRoomType($type);
            $this->assertWithinRoomTypeLimit($type);
        });
    }

    public function assertAllowsRoomType($type)
    {
        if (! $type) {
            throw new \Exception("That type of room cannot be added.");
        }
    }

    public function assertWithinRoomTypeLimit($type)
    {
        $count = $this->model->roomsCount()->byType($type->id);

        if ($count >= $type->pivot->available_rooms) {
            throw new \Exception("The maximum number of $type->name rooms allowed has been reached.");
        }
    }

    private function getRooms()
    {
        return Room::whereIn('id', $this->roomIds)->get();
    }
}
