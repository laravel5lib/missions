<?php

namespace App\Http\Controllers\Api\Rooming\Plans;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Repositories\Rooming\Room;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\RoomRequest;
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
                      ->filter($request)
                      ->inPlan($planId)
                      ->paginate($request->get('per_page'));

        return $this->response->paginator($rooms, new RoomTransformer);
    }

    public function show($planId, $id)
    {
        $room = $this->room->find($id)->get();

        return $this->response->item($room, new RoomTransformer);
    }

    public function store($planId, RoomRequest $request)
    {
        $room = $this->room->make($request)->forPlan($planId)->get();

        return $this->response->item($room, new RoomTransformer);
    }

    public function update($planId, $id, RoomRequest $request)
    {
        $room = $this->room->find($id)->modify($request)->get();

        return $this->response->item($room, new RoomTransformer);
    }

    public function destroy($planId, $id)
    {
        $this->room->find($id)->delete();

        return $this->response->noContent();
    }
}
