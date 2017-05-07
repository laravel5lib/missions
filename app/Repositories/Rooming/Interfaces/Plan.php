<?php

namespace App\Repositories\Rooming\Interfaces;

use Illuminate\Http\Request;
use App\Http\Requests\v1\RoomingPlanRequest;

interface Plan {
    public function filter(Request $request);
    public function find($id);
    public function make(RoomingPlanRequest $request);
    public function modify(RoomingPlanRequest $request);
    public function get();
    public function all();
    public function paginate($perPage);
    public function delete();
    public function lock();
    public function unlock();
    public function copy(Request $request);
    public function useForAccommodation($accommodationId);
    public function stopUseForAccommodation($accommodationId);
}