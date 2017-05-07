<?php

namespace App\Repositories\Rooming\Interfaces;

use Illuminate\Http\Request;
use App\Http\Requests\v1\RoomRequest;

interface Room {
    public function filter(Request $request);
    public function find($id);
    public function make(RoomRequest $request);
    public function modify(RoomRequest $request);
    public function get();
    public function all();
    public function paginate($perPage);
    public function delete();
    public function inPlan($planId);
    public function forPlan($planId);
    public function addToPlan($planId);
    public function removeFromPlan($planId);
    public function moveToPlan($planId);
}