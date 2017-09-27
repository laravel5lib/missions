<?php

namespace App\Http\Controllers\Api\Rooming;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\RoomRequest;
use App\Http\Transformers\v1\RoomTransformer;
use App\Repositories\Rooming\Interfaces\Room;
use App\Services\Rooming\ManageRooms;

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
                      ->filter($request->all())
                      ->paginate($request->get('per_page'));

        return $this->response->paginator($rooms, new RoomTransformer);
    }

    public function show($id)
    {
        $room = $this->room->getByid($id);

        return $this->response->item($room, new RoomTransformer);
    }

    public function store(RoomRequest $request)
    {
        $room = $this->room->create([
            'room_type_id' => $request->get('room_type_id'),
            'label'        => $request->get('label')
        ]);

        return $this->response->item($room, new RoomTransformer);
    }

    public function update($id, RoomRequest $request)
    {
        $room = $this->room
                     ->update([
                        'room_type_id' => $request->get('room_type_id'),
                        'label'        => $request->get('label')
                     ], $id);

        return $this->response->item($room, new RoomTransformer);
    }

    public function destroy($id)
    {
        $this->room->delete($id);

        return $this->response->noContent();
    }
}
