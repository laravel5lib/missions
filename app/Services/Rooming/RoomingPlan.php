<?php
namespace App\Services\Rooming;

use App\Models\v1\RoomingPlan as Model;

class RoomingPlan
{
    protected $plan;

    function __construct(Model $plan)
    {
        $this->plan = $plan;
    }

    public function find($id)
    {
        $this->plan->find($id);

        return $this;
    }

    public function filter($request)
    {
        $this->plan->filter($request);

        return $this;
    }

    public function get()
    {
        return $this->plan->paginate(10);
    }

    public function make($request)
    {
        $plan = $this->plan->create([]);
    }

    public function modify($request)
    {
        $plan = $this->plan->update([]);
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
        $plan = $this->plan->get();

        // $copy = new Model;

        // $copy->create([
        //     'name' = $request->get('name', $plan->name),
        //     'group_id' => $plan->group_id,
        //     'campaign_id' => $plan->campaign_id,
        //     'short_desc' => $plan->short_desc,
        //     'locked' => $plan->locked
        // ]);

        $rooms = $plan->rooms()->pluck('id')->toArray();

        $copy->rooms()->attach($rooms);
    }

    public function destroy()
    {
        $this->plan->rooms()->detach();
        $this->plan->delete();

        return true;
    }
}