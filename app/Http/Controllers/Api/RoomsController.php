<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Services\Rooming\Room;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\RoomRequest;
use App\Http\Transformers\v1\RoomTransformer;

class RoomsController extends Controller
{
    private $room;

    function __construct(Room $room)
    {
        $this->room = $room;
    }

    public function index($planId, Request $request)
    {
        $rooms = $this->room
                      ->inPlan($planId)
                      ->filter($request->all())
                      ->paginate($request->get('per_page'));

        return $this->response->paginator($rooms, new RoomTransformer);
    }

    public function show($planId, $id)
    {
        $room = $this->room->inPlan($planId)->find($id)->get();

        return $this->response->item($room, new RoomTransformer);
    }

    public function store($planId, RoomRequest $request)
    {
        $room = $this->room->make($request)->forPlan($planId)->get();

        return $this->response->item($room, new RoomTransformer);
    }

    public function update($planId, $id, RoomRequest $request)
    {
        $room = $this->room
                     ->inPlan($planId)
                     ->find($id)
                     ->modify($request)
                     ->get();

        return $this->response->item($room, new RoomTransformer);
    }

    public function destroy($planId, $id)
    {
        $room->inPlan($planId)->find($id)->delete();

        return $this->response->noContent();
    }
}
