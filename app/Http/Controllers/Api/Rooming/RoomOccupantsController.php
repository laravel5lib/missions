<?php

namespace App\Http\Controllers\Api\Rooming;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\OccupantRequest;
use App\Http\Transformers\v1\OccupantTransformer;
use App\Repositories\Rooming\Interfaces\Occupant;

class RoomOccupantsController extends Controller
{
    protected $occupant;

    function __construct(Occupant $occupant)
    {
        $this->occupant = $occupant;
    }

    /**
     * Get all occupants in the room.
     * 
     * @param  string  $roomId
     * @param  Request $request
     * @return Response
     */
    public function index($roomId, Request $request)
    {
        $occupants = $this->occupant->getAll($roomId);

        return $this->response->collection($occupants, new OccupantTransformer);
    }

    /**
     * Get specific occupant in room.
     * 
     * @param  string $roomId
     * @param  string $reservationId
     * @return Response
     */
    public function show($roomId, $reservationId)
    {
        $occupant = $this->occupant->getById($roomId, $reservationId);

        return $this->response->item($occupant, new OccupantTransformer);
    }

    /**
     * Add a new occupant to the room.
     * 
     * @param  string  $roomId
     * @param  Request $request
     * @return Response
     */
    public function store($roomId, OccupantRequest $request)
    {
        $reservations = $request->get('reservations') ?: $request->get('reservation_id');

        $this->occupant->create($reservations, $roomId);

        if ($request->has('reservation_id') && $request->get('room_leader') === true) {
            $this->occupant->promote($roomId, $request->get('reservation_id'));
        }

        $occupants = $this->occupant->getAll($roomId);

        return $this->response->collection($occupants, new OccupantTransformer);
    }

    /**
     * Update the occupant in the room.
     * 
     * @param  string  $roomId
     * @param  string  $reservationId
     * @param  Request $request
     * @return Response
     */
    public function update($roomId, $reservationId, OccupantRequest $request)
    {
        if ($request->has('room_leader')) {

            $isLeader = $request->get('room_leader');

            $isLeader ? $this->occupant->promote($roomId, $reservationId) 
                : $this->occupant->demote($roomId, $reservationId);

        }

        $occupant = $this->occupant->getById($roomId, $reservationId);

        return $this->response->item($occupant, new OccupantTransformer);
    }

    /**
     * Remove the occupant from the room.
     * 
     * @param  string $roomId
     * @param  string $reservationId
     * @return Response
     */
    public function destroy($roomId, $reservationId = null)
    {   
        $reservations = $reservationId ?: $request->get('reservations');

        $this->occupant->delete($roomId, $reservations);

        return $this->response->noContent();
    }
}
