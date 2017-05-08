<?php

namespace App\Repositories\Rooming\Interfaces;

use Illuminate\Http\Request;
use App\Http\Requests\v1\RoomTypeRequest;

interface Type {
    public function filter(Request $request);
    public function find($id);
    public function make(RoomTypeRequest $request);
    public function modify(RoomTypeRequest $request);
    public function get();
    public function all();
    public function paginate($perPage);
    public function delete();
}