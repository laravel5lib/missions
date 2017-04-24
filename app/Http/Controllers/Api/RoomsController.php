<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Models\v1\Room;
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

    public function index(Request $request)
    {
        $rooms = $this->room
            ->filter($request->all())
            ->paginate($request->get('per_page', 10));

        return $this->response->paginator($rooms, new RoomTransformer);
    }

    public function show($id)
    {
        $room = $this->room->findOrFail($id);

        return $this->response->item($room, new RoomTransformer);
    }

    public function store(RoomRequest $request)
    {
        $room = $this->room->create([
            'label' => $request->get('label'),
            'room_type_id' => $request->get('room_type_id')
        ]);

        return $this->response->item($room, new RoomTransformer);
    }

    public function update(RoomRequest $request, $id)
    {
        $room = $this->room->findOrFail($id);

        $room->update([
            'label' => $request->get('label', $room->label),
            'room_type_id' => $request->get('room_type_id', $room->room_type_id)
        ]);

        return $this->response->item($room, new RoomTransformer);
    }

    public function destroy($id)
    {
        $room = $this->room->findOrFail($id);

        $room->delete();

        return $this->response->noContent();
    }
}
