<?php

namespace App\Http\Controllers\Api\Rooming\Plans;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Repositories\Rooming\Room;
use App\Http\Controllers\Controller;
use App\Http\Transformers\v1\RoomTransformer;

class RoomsController extends Controller
{
    protected $room;

    function __construct(Room $room)
    {
        $this->room = $room;
    }
    
    public function index($planId, Request $request)
    {
        $rooms = $this->room
                      ->inPlan($planId)
                      ->filter($request)
                      ->paginate($request->get('per_page'));

        return $this->response->paginator($rooms, new RoomTransformer);
    }
}
