<?php

namespace App\Repositories\Rooming\Interfaces;

interface Occupant {
    public function getById($roomId, $id);
    public function getAll($roomId);
    public function create($data, $roomId);
    public function delete($roomId, $ids);
    public function promote($roomId, $id);
    public function demote($roomId, $id);
}