<?php
namespace App\Services\Rooming;

use Illuminate\Http\Request;
use App\Http\Requests\v1\RoomingPlanRequest;
use App\Models\v1\RoomingPlan as RoomingModel;

class RoomingPlan
{
    protected $plan;

    function __construct(RoomingModel $plan)
    {
        $this->plan = $plan;
    }

    /**
     * Find a plan by it's id.
     * 
     * @param  String $id
     * @return $this
     */
    public function find($id)
    {
        $this->plan->whereId($id);

        return $this;
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
     * Return the plan results
     * 
     * @param  integer $perPage
     * @return EloquentCollection
     */
    public function get($perPage = 10)
    {
        // if ($this->plan) 
        //     return $this->plan->paginate($perPage);

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
    protected function modify(RoomingPlanRequest $request)
    {
        if ( ! $this->plan->id)
            throw new \Exception('Bad Method Call');

        $plan = $this->plan->update([
            'name'        => trim($request->get('name', $this->plan->name)),
            'short_desc'  => trim($request->get('short_desc', $this->plan->short_desc)),
            'group_id'    => $request->get('group_id'),
            'campaign_id' => $request->get('campaign_id')
        ]);

        return $plan;
    }

    public function lock()
    {
        return $this->plan->update(['locked' => true]);
    }

    public function unlock()
    {
        return $this->plan->update(['locked' => true]);
    }

    public function useForAccommodation($accomodationId)
    {
        $rooms = $this->plan->rooms();

        foreach ($rooms as $room) {
            $room->accomodations()->attach($accomodationId);
        }
    }

    public function stopUseForAccommodation($accomodationId)
    {
        $rooms = $this->plan->rooms();

        foreach ($rooms as $room) {
            $room->accomodations()->detach($accomodationId);
        }
    }

    public function copy()
    {
        // $plan = $this->plan->get();

        // $copy = RoomingModel::create([
        //     'name' = $request->get('name', $plan->name),
        //     'group_id' => $plan->group_id,
        //     'campaign_id' => $plan->campaign_id,
        //     'short_desc' => $plan->short_desc,
        //     'locked' => $plan->locked
        // ]);

        // $rooms = $plan->rooms()->pluck('id')->toArray();

        // $copy->rooms()->attach($rooms);
    }

    public function delete()
    {
        $this->plan->rooms()->detach();
        $this->plan->delete();

        return true;
    }
}