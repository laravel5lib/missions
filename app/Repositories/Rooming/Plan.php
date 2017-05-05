<?php

namespace App\Repositories\Rooming;

use Illuminate\Http\Request;
use App\Models\v1\RoomingPlan;
use App\Http\Requests\v1\RoomingPlanRequest;

class Plan 
{
    protected $plan;

    function __construct(RoomingPlan $plan)
    {
        $this->plan = $plan;
    }

    /**
     * Filter plans by request.
     * 
     * @param  Request $request
     * @return $this
     */
    public function filter(Request $request)
    {
        $this->plan->filter($request->all());

        return $this;
    }

    /**
     * Find a plan by it's id.
     * 
     * @param  String $id
     * @return $this
     */
    public function find($id)
    {
        $this->plan = $this->plan->withTrashed()->findOrFail($id);

        return $this;
    }

    /**
     * Return plan.
     * 
     * @return Mixed
     */
    public function get()
    {
        return $this->plan;
    }

    /**
     * Get all plans.
     * 
     * @return EloquentCollection
     */
    public function all()
    {
        return $this->plan->all();
    }

    /**
     * Return paginated plans.
     * 
     * @param  integer $perPage
     * @return EloquentCollection
     */
    public function paginate($perPage = 10)
    {
        return $this->plan->paginate($perPage);
    }

    /**
     * Make a new plan and save in storage.
     * 
     * @param  RoomingPlanRequest $request
     * @return EloquentCollection
     */
    public function make(RoomingPlanRequest $request)
    {
        $plan = $this->plan->create([
            'name'        => trim($request->get('name')),
            'short_desc'  => trim($request->get('short_desc', 'no description')),
            'group_id'    => $request->get('group_id'),
            'campaign_id' => $request->get('campaign_id')
        ]);

        return $plan;
    }

    /**
     * Modify an existing plan and update in storage.
     * 
     * @param  RoomingPlanRequest $request
     * @return EloquentCollection
     */
    public function modify(RoomingPlanRequest $request)
    {
        $this->plan->update([
            'name'        => trim($request->get('name', $this->plan->name)),
            'short_desc'  => trim($request->get('short_desc', $this->plan->short_desc)),
            'group_id'    => $request->get('group_id', $this->plan->group_id),
            'campaign_id' => $request->get('campaign_id', $this->plan->campaign_id)
        ]);

        return $this->plan;
    }

    /**
     * Lock a rooming plan so it can no longer be modified.
     * 
     * @return Mixed
     */
    public function lock()
    {
        return $this->plan->update(['locked' => true]);
    }

    /**
     * Unlock a locked rooming plan so it can be modified.
     * 
     * @return Mixed
     */
    public function unlock()
    {
        return $this->plan->update(['locked' => true]);
    }

    /**
     * Use a plan with the given accommodation.
     * 
     * @param  String $accomodationId
     * @return mixed
     */
    public function useForAccommodation($accomodationId)
    {
        $rooms = $this->plan->rooms();

        foreach ($rooms as $room) {
            $room->accomodations()->attach($accomodationId);
        }
    }

    /**
     * Stop using a plan with the given accommodation.
     * 
     * @param  String $accomodationId
     * @return mixed
     */
    public function stopUseForAccommodation($accomodationId)
    {
        $rooms = $this->plan->rooms();

        foreach ($rooms as $room) {
            $room->accomodations()->detach($accomodationId);
        }
    }

    /**
     * Copy a rooming plan with rooms.
     * 
     * @param  Request $request
     * @return Mixed
     */
    public function copy(Request $request)
    {
        $plan = $this->plan;

        $copy = RoomingPlan::create([
            'name' => $request->get('name', $plan->name),
            'group_id' => $plan->group_id,
            'campaign_id' => $plan->campaign_id,
            'short_desc' => $plan->short_desc,
            'locked' => $plan->locked
        ]);

        $rooms = $plan->rooms()->pluck('id')->toArray();

        $copy->rooms()->attach($rooms);
    }

    /**
     * Delete a rooming plan.
     * 
     * @return Boolean
     */
    public function delete()
    {
        $this->plan->rooms()->detach();
        $this->plan->delete();

        return true;
    }

}