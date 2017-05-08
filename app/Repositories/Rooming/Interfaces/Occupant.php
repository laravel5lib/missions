<?php

namespace App\Repositories\Rooming\Interfaces;

use Illuminate\Http\Request;

interface Occupant {
    public function filter(Request $request);
    public function find($id);
    public function addToRoom($roomId);
    public function removeFromRoom($roomId);
    public function promote($id);
    public function demote($id);
    public function get();
    public function all();
}