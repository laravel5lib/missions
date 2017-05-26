<?php

namespace App\Services\Rooming;

use App\Repositories\Rooming\Interfaces\Plan;
use App\Repositories\Rooming\Interfaces\Accommodation;

class ManageRooms
{
    protected $roomableType;
    protected $roomableId;

    function __construct($roomableType, $roomableId)
    {
        $this->roomableType = $roomableType;
        $this->roomableId = $roomableId;
    }

    /**
     * Add rooms to the given repository.
     * 
     * @param  array|string $rooms
     * @return boolean
     */
    public function add($rooms)
    {
        return $this->getRepository()
                    ->addRooms($rooms, $this->roomableId);
    }

    /**
     * Remove rooms from the given repository.
     * 
     * @param  array|string $rooms
     * @return boolean
     */
    public function remove($rooms)
    {
        return $this->getRepository()
                    ->removeRooms($rooms, $this->roomableId);
    }

    /**
     * Get the repository to use.
     * 
     * @return Repo
     */
    private function getRepository()
    {
        $repositories = [
            'plans' => Plan::class,
            'accommodations' => Accommodation::class
        ];

        return app()->make($repositories[$this->roomableType]);
    }

}