<?php

namespace App\Repositories\Rooming;

use App\Models\v1\Room;
use App\Repositories\Rooming\Interfaces\Occupant;

class EloquentOccupant implements Occupant
{

    protected $model;
    
    function __construct(Room $model)
    {
        $this->model = $model;
    }

    // ->wherePivot('leader', true); ->wherePivot('leader', false);

    public function getAll($roomId)
    {
        return $this->model->findOrFail($roomId)->occupants;
    }

    public function getById($roomId, $id)
    {
        return $this->model
                    ->findOrFail($roomId)
                    ->occupants()
                    ->findOrFail($id);
    }

    public function create($data, $roomId)
    {
        $room = $this->model->findOrFail($roomId);

        $room->validateOccupants($data);

        $room->occupants()->attach($data);
    }

    public function delete($roomId, $ids)
    {
        $this->model->findOrFail($roomId)->occupants()->detach($ids);
    }

    public function promote($roomId, $id)
    {
        return $this->model
             ->findOrFail($roomId)
             ->occupants()
             ->updateExistingPivot($id, ['room_leader' => true]);
    }

    public function demote($roomId, $id)
    {
        return $this->model
             ->findOrFail($roomId)
             ->occupants()
             ->updateExistingPivot($id, ['room_leader' => false]);
    }
}
