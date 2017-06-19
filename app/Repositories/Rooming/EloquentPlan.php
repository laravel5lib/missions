<?php

namespace App\Repositories\Rooming;

use App\Traits\Roomable;
use App\Models\v1\RoomingPlan;
use App\Repositories\EloquentRepository;
use App\Repositories\Rooming\Interfaces\Plan;

class EloquentPlan extends EloquentRepository implements Plan
{
    use Roomable;

    protected $model;

    protected $attributes = [
        'name', 'short_desc', 'group_id', 'campaign_id'
    ];

    function __construct(RoomingPlan $model)
    {
        $this->model = $model;
    }

    /**
     * Modify an existing plan and update in storage.
     * 
     * @param  RoomingPlanRequest $request
     * @return EloquentCollection
     */
    public function update(array $data, $id, $attribute = 'id')
    {
        $plan = $this->getById($id);

        $plan->update($this->sanitize($data));

        return $plan;
    }

    /**
     * Lock a rooming plan so it can no longer be modified.
     * 
     * @return Mixed
     */
    public function lock($id)
    {
        $plan = $this->getById($id);

        $plan->update(['locked' => true]);

        return $plan;
    }

    /**
     * Unlock a locked rooming plan so it can be modified.
     * 
     * @return Mixed
     */
    public function unlock($id)
    {
        $plan = $this->getById($id);

        $plan->update(['locked' => true]);

        return $plan;
    }

}