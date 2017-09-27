<?php

namespace App\Repositories\Rooming\Interfaces;

interface Room
{
    public function filter(array $data);
    public function getById($id);
    public function create(array $data);
    public function update(array $data, $id, $attribute);
    public function getAll();
    public function paginate($perPage);
    public function delete($ids);
}
