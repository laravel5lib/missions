<?php

namespace App\Traits;

trait Roomable
{   
    /**
     * Add Rooms
     * @param  array|string $rooms
     * @param  string $id
     * @return boolean
     */
    public function addRooms($rooms, $id)
    {
        $model = $this->getByid($id);

        $model->validateRooms($rooms);

        $model->rooms()->sync($rooms, false);

        return true;
    }

    /**
     * Remove rooms.
     * 
     * @param  array|string $rooms
     * @param  string $id
     * @return boolean
     */
    public function removeRooms($rooms, $id)
    {
        $model = $this->getByid($id);

        $model->rooms()->detach($rooms);

        return true;
    }
}