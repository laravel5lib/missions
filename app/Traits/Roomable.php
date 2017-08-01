<?php

namespace App\Traits;

trait Roomable
{

    public function addRooms($rooms, $id)
    {
        $model = $this->getByid($id);

        $model->validateRooms($rooms)->validate();

        $model->rooms()->sync($rooms, false);

        return true;
    }

    public function removeRooms($rooms, $id)
    {
        $model = $this->getByid($id);

        $model->rooms()->detach($rooms);

        return true;
    }

    public function addRoomType(array $data, $id)
    {
        $model = $this->getById($id);

        $model->availableRoomTypes()->sync([
            $data['room_type_id'] => [ 'available_rooms' => $data['available_rooms'] ]
            ], false);

        return $model;
    }

    public function removeRoomType($typeId, $id)
    {
        $model = $this->getById($id);

        $model->availableRoomTypes()->detach($typeId);

        return $model;
    }

    public function updateRoomType(array $data, $typeId, $id)
    {
        $model = $this->getById($id);

        $model->availableRoomTypes()->updateExistingPivot($typeId, [
            'available_rooms' => $data['available_rooms']
        ]);

        return $model;
    }
}
