<?php

namespace App\Http\Controllers\Api\Rooming\Roomable;

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

    public function index($roomableType = null, $roomableId = null, Request $request)
    {
        $request->merge([$roomableType => $roomableId]);

        $rooms = $this->room
                      ->filter($request->all())
                      ->paginate($request->get('per_page'));

        return $this->response->paginator($rooms, new RoomTransformer);
    }

    public function show($roomableType, $roomableId, $id)
    {
        $room = $this->room->filter([$roomableType => $roomableId])->getByid($id);

        return $this->response->item($room, new RoomTransformer);
    }

    public function store($roomableType, $roomableId, Request $request)
    {
        (new ManageRooms($roomableType, $roomableId))->add($request->get('room_ids'));

        return $this->response->item($room, new RoomTransformer);
    }

    public function update($roomableType, $roomableId, $id, RoomRequest $request)
    {
        $room = $this->room
                     ->filter([$roomableType => $roomableId])
                     ->update([
                        'room_type_id' => $request->get('room_type_id'),
                        'label'        => $request->get('label')
                    ], $id);

        return $this->response->item($room, new RoomTransformer);
    }

    public function destroy($roomableType, $roomableId, $id)
    {
        (new ManageRooms($roomableType, $roomableId))->remove($id);

        return $this->response->noContent();
    }
}
