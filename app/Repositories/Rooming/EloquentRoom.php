<?php

namespace App\Repositories\Rooming;

use Illuminate\Support\Facades\DB;
use App\Models\v1\Room as RoomModel;
use App\Repositories\EloquentRepository;
use App\Repositories\Rooming\Interfaces\Room;

class EloquentRoom extends EloquentRepository implements Room
{

    protected $model;

    protected $attributes = ['label', 'room_type_id'];

    function __construct(RoomModel $room)
    {
        $this->model = $room;
    }

    public function getAll()
    {
        return $this->model->withCount('occupants')->get();
    }

    public function withOccupants()
    {
        return $this->model->withCount('occupants')->has('occupants')->with('occupants')->get();
    }

    public function getById($id)
    {
        return $this->model->withTrashed()->withCount('occupants')->findOrFail($id);
    }

    public function paginate($perPage = 15, $columns = array('*'))
    {
        return $this->model->withCount('occupants')->paginate($perPage, $columns);
    }

    public function delete($id)
    {
        DB::transaction(function () use ($id) {
            $this->getById($id)->occupants()->detach();
            $this->model->delete($id);
        });
    }
}
