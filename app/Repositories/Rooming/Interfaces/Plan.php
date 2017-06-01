<?php

namespace App\Repositories\Rooming\Interfaces;

use Illuminate\Http\Request;
use App\Http\Requests\v1\RoomingPlanRequest;

interface Plan {
    public function filter(array $data);
    public function getById($id);
    public function create(array $data);
    public function update(array $data, $id);
    public function getAll();
    public function paginate($perPage, $columns);
    public function delete($ids);
    public function lock($id);
    public function unlock($id);
    public function addRooms($rooms, $id);
    public function removeRooms($rooms, $id);
    public function addRoomType(array $data, $id);
    public function removeRoomType($typeId, $id);
    public function updateRoomType(array $data, $id);
}