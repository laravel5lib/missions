<?php

namespace App\Http\Controllers\Api\Rooming;

use App\Http\Requests;
use App\Repositories\Rooming\Interfaces\Room;
use Illuminate\Http\Request;
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

    public function index(Request $request)
    {
        $rooms = $this->room
                      ->filter($request)
                      ->paginate($request->get('per_page'));

        return $this->response->paginator($rooms, new RoomTransformer);
    }

    public function show($id)
    {
        $room = $this->room->find($id)->get();

        return $this->response->item($room, new RoomTransformer);
    }

    public function store(RoomRequest $request)
    {
        $room = $this->room->make($request)->get();

        return $this->response->item($room, new RoomTransformer);
    }

    public function update($id, RoomRequest $request)
    {
        $room = $this->room->find($id)->modify($request)->get();

        return $this->response->item($room, new RoomTransformer);
    }

    public function destroy($id)
    {
        $room->find($id)->delete();

        return $this->response->noContent();
    }
}
