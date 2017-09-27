<?php

namespace App\Services\Rooming;

use App\Services\Rooming\ManageRooms;
use App\Repositories\Rooming\Interfaces\Room;

class ManagePlans
{
    protected $roomableType;
    protected $roomableId;

    function __construct($roomableType, $roomableId)
    {
        $this->roomableType = $roomableType;
        $this->roomableId = $roomableId;
    }

    /**
     * Add plan to the given repository.
     *
     * @param  array|string $rooms
     * @return boolean
     */
    public function use($plans)
    {
        $roomIds = $this->getRoomIds($plans);

        (new ManageRooms($this->roomableType, $this->roomableId))->add($roomIds);
    }

    /**
     * Remove plan from the given repository.
     *
     * @param  array|string $rooms
     * @return boolean
     */
    public function stop($plans)
    {
        $roomIds = $this->getRoomIds($plans);

        (new ManageRooms($this->roomableType, $this->roomableId))->remove($roomIds);
    }

    /**
     * Get the repository to use.
     *
     * @return Repo
     */
    private function getRoomIds($plans)
    {
        $room = app()->make(Room::class);

        return $room->filter(['plans' => $plans])->getAll()->pluck('id')->all();
    }
}
