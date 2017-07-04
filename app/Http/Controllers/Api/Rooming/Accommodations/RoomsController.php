<?php

namespace App\Http\Controllers\Api\Rooming\Accommodations;

use App\Http\Transformers\v1\RoomTransformer;
use App\Repositories\Rooming\Interfaces\Room;
use App\Services\Rooming\ManageRooms;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoomsController extends Controller
{
    private $room;

    public function __construct(Room $room)
    {
        $this->room = $room;
    }

    public function index($accommodationId, Request $request)
    {
        $request->merge(['accommodations' => $accommodationId]);

        $rooms = $this->room
            ->filter($request->all())
            ->paginate($request->get('per_page'));

        return $this->response->paginator($rooms, new RoomTransformer);
    }

    public function show($accommodationId, $id)
    {
        $room = $this->room->filter(['accommodations' => $accommodationId])->getByid($id);

        return $this->response->item($room, new RoomTransformer);
    }

    public function store($accommodationId, Request $request)
    {
        $manager = new ManageRooms('accommodations', $accommodationId);
        $manager->add($request->get('room_ids', []));

        return $this->response->created();
    }

    public function destroy($accommodationId, $id)
    {
        $manager = new ManageRooms('accommodations', $accommodationId);
        $manager->remove([$id]);

        return $this->response->noContent();
    }
}
