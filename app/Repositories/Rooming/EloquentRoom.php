<?php

namespace App\Repositories\Rooming;

use Illuminate\Http\Request;
use App\Models\v1\Room as RoomModel;
use App\Http\Requests\v1\RoomRequest;
use App\Repositories\Rooming\Interfaces\Plan;
use App\Repositories\Rooming\Interfaces\Room;

class EloquentRoom implements Room {

    protected $room;
    protected $plan;

    function __construct(RoomModel $room, Plan $plan)
    {
        $this->room = $room;
        $this->plan = $plan;
    }

    /**
     * Get rooms in plan.
     * 
     * @param  String $planId
     * @return EloquentCollection
     */
    public function inPlan($planId)
    {
        $this->plan = $this->plan->find($planId)->get();

        return $this->plan->rooms();
    }

    /**
     * Find room by it's ID.
     * 
     * @param  String $id
     * @return $this
     */
    public function find($id)
    {
        $this->room = $this->room->withTrashed()->findOrFail($id);

        return $this;
    }

    /**
     * Filter rooms by request.
     * 
     * @param  String $id
     * @return $this
     */
    public function filter(Request $request)
    {
        $this->room->filter($request->all());

        return $this;
    }

    /**
     * Get all rooms.
     * 
     * @param  String $id
     * @return EloquentCollection
     */
    public function all()
    {
        return $this->room->all();
    }

    /**
     * Get paginated list of rooms.
     * 
     * @param  String $id
     * @return EloquentCollection
     */
    public function paginate($perPage = 10)
    {
        return $this->room->paginate($perPage);
    }

    /**
     * Make a new room and save it storage.
     * 
     * @param  RoomRequest $request
     * @return $this
     */
    public function make(RoomRequest $request)
    {
        $this->room = $this->room->create([
            'label' => $request->get('label'),
            'room_type_id' => $request->get('room_type_id')
        ]);

        return $this;
    }

    /**
     * Modify an existing room.
     * 
     * @param  RoomRequest $request
     * @return $this
     */
    public function modify(RoomRequest $request)
    {
        $this->room->update([
            'label' => $request->get('label', $this->room->label),
            'room_type_id' => $request->get('room_type_id', $this->room->type)
        ]);   

        if ($request->has('rooming_plan_id')) {
            $this->moveToPlan($request->get('rooming_plan_id'));
        }

        return $this;
    }

    /**
     * Delete a room.
     * 
     * @return Boolean
     */
    public function delete()
    {
        $this->room->delete();

        return true;
    }

    /**
     * Move room to another plan.
     * 
     * @param  String $id
     * @return $this
     */
    public function moveToPlan($id)
    {
        $this->removeFromPlan($this->plan->id);
        $this->addToPlan($id);

        return $this;
    }

    /**
     * Alias of addToPlan
     * 
     * @param  String $planId
     * @return $this
     */
    public function forPlan($planId)
    {
        return $this->addToPlan($planId);
    }

    /**
     * Add room to a plan.
     * 
     * @param String $planId
     * @return $this
     */
    public function addToPlan($planId)
    {
        $this->room->plans()->attach($planId);

        return $this;
    }

    /**
     * Remove room from a plan.
     * 
     * @param  String $planId
     * @return $this
     */
    public function removeFromPlan($planId)
    {
        $this->room->plans()->detach($planId);

        return $this;
    }

    /**
     * Get the result
     * 
     * @return response
     */
    public function get()
    {
        return $this->room;
    }
    
}