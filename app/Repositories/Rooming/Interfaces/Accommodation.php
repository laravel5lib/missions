<?php

namespace App\Repositories\Rooming\Interfaces;

interface Accommodation
{
    public function filter(array $data);
    public function getById($id);
    public function create(array $data);
    public function update(array $data, $id);
    public function getAll();
    public function paginate($perPage, $columns);
    public function delete($ids);
    public function addRooms($rooms, $id);
    public function removeRooms($rooms, $id);
    public function addRoomType(array $data, $id);
    public function removeRoomType($typeId, $id);
    public function updateRoomType(array $data, $typeId, $id);
}
