<?php

namespace App\Http\Controllers\Api\Rooming\Roomable;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\RoomRequest;
use App\Services\Rooming\ManageRooms;
use App\Http\Transformers\v1\RoomTransformer;
use App\Repositories\Rooming\Interfaces\Room;

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

    public function store($roomableType, $roomableId, RoomRequest $request)
    {
        DB::transaction(function () use ($request, $roomableType, $roomableId) {
            $room = $this->room->create([
                'room_type_id' => $request->get('room_type_id'),
                'label'        => $request->get('label')
            ]);

            (new ManageRooms($roomableType, $roomableId))->add([$room->id]);

            return $this->response->item($room, new RoomTransformer);
        });
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
        DB::transaction(function () use ($roomableType, $roomableId, $id) {
            $this->room
                 ->filter([$roomableType => $roomableId])
                 ->delete($id);

            (new ManageRooms($roomableType, $roomableId))->remove($id);
        });

        return $this->response->noContent();
    }
}
