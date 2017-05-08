<?php

namespace App\Http\Controllers\Api\Rooming;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Rooming\Interfaces\Room;
use App\Http\Transformers\v1\OccupantTransformer;
use App\Repositories\Rooming\Interfaces\Occupant;

class RoomOccupantsController extends Controller
{
    function __construct(Occupant $occupant, Room $room)
    {
        $this->occupant = $occupant;
        $this->room = $room;
    }

    public function index($roomId, Request $request)
    {
        $request->merge(['rooms' => $roomId]);

        $occupants = $this->occupant
                          ->filter($request->all())
                          ->paginate($request->get('per_page'));

        return $this->response->paginator($occupants, new OccupantTransformer);
    }

    public function show($roomId, $reservationId)
    {
        $occupant = $this->occupant
                          ->filter(['rooms' => $roomId])
                          ->getById($reservationId);

        return $this->response->item($occupant, new OccupantTransformer);
    }

    public function store($roomId, Request $request)
    {
        $reservations = $request->get('reservations') ?: $request->get('reservation_id');

        $this->room->addOccupants($roomId, $reservations);

        return $this->response->created();
    }

    public function update($roomId, $reservationId, Request $request)
    {
        $occupant = $this->occupant
                         ->filter(['rooms' => $roomId])
                         ->getById($reservationId);

        if ($request->has('room_leader')) {

            $isLeader = $request->get('room_leader');

            $isLeader ? $this->occupant->promote($roomId, $reservationId) 
                : $this->occupant->demote($roomId, $reservationId);

        }

        return $this->response->item($occupant, new OccupantTransformer);
    }

    public function destroy($roomId, $reservationId = null)
    {   
        $reservations = $reservationId ?: $request->get('reservations');

        $this->room->removeOccupants($roomId, $reservations);

        return $this->response->noContent();
    }
}
