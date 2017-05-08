<?php

namespace App\Repositories\Rooming\Interfaces;

interface Occupant {
    public function filter(array $data);
    public function getById($id);
    public function getAll();
    public function create(array $data);
    public function update(array $data, $id, $attribute);
    public function delete($ids);
    public function promote($roomId, $id);
    public function demote($roomId, $id);
}