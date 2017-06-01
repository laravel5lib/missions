<?php

namespace App\Services\Rooming;

use App\Models\v1\Room;
use App\Models\v1\RoomingPlan;

class ValidatesRooms
{
    protected $roomIds;
    protected $plan;

    function __construct($roomIds, RoomingPlan $plan)
    {
        $this->roomIds = $roomIds;
        $this->plan = $plan;
    }

    /**
     * Run all assertions.
     */
    public function validate()
    {
        $this->getRooms()->each(function($room) 
        {
            $type = $this->plan->availableRoomTypes()->where('id', $room->room_type_id)->first();

            $this->assertAllowsRoomType($type);
            $this->assertWithinRoomTypeLimit($type);
        });
    }

    public function assertAllowsRoomType($type)
    {
        if (! $type) {
            throw new \Exception("That type of room cannot be added to this plan.");
        }
    }

    public function assertWithinRoomTypeLimit($type)
    {
        $count = $this->plan->roomsCount()->byType($type->id);

        if ( $count >= $type->pivot->available_rooms ) {
            throw new \Exception("This plan has the maximum number of $type->name rooms allowed.");
        }
    }

    private function getRooms()
    {
        return Room::whereIn('id', $this->roomIds)->get();
    }
}