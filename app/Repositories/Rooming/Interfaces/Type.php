<?php

namespace App\Repositories\Rooming\Interfaces;

use Illuminate\Http\Request;
use App\Http\Requests\v1\RoomTypeRequest;

interface Type {
    public function getAll();
    public function getById($id);
    public function create(array $data);
    public function update(array $data, $id, $attribute);
    public function delete($ids);
    public function paginate($perPage);
    public function filter(array $data);
    public function belongsToPlan($planId);
    public function belongsToAccommodation($accommodationId);
}