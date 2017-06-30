<?php

namespace App\Http\Controllers\Api\Rooming;

use Illuminate\Http\Request;
use App\Jobs\ExportRoomingPlans;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\ExportRequest;
use App\Http\Requests\v1\RoomingPlanRequest;
use App\Repositories\Rooming\Interfaces\Plan;
use App\Http\Transformers\v1\RoomingPlanTransformer;

class PlansController extends Controller
{
    protected $plan;

    function __construct(Plan $plan)
    {
        $this->plan = $plan;
    }

    /**
     * Get all plans.
     * 
     * @param  Request $request
     * @return Dingo\Api\Http\Response
     */
    public function index(Request $request)
    {
        $plans = $this->plan
                      ->filter(array_filter($request->all()))
                      ->paginate($request->get('per_page', 10));

        return $this->response->paginator($plans, new RoomingPlanTransformer);
    }

    /**
     * Get specific plan.
     *
     * @param  String  $id
     * @param  Request $request
     * @return Dingo\Api\Http\Response
     */
    public function show($id)
    {
        $plan = $this->plan->getById($id);

        return $this->response->item($plan, new RoomingPlanTransformer);
    }

    /**
     * Create a new plan and save in storage.
     * 
     * @param  RoomingPlanRequest $request
     * @return Dingo\Api\Http\Response
     */
    public function store(RoomingPlanRequest $request)
    {
        $plan = $this->plan->create([
            'name'        => $request->get('name'),
            'short_desc'  => $request->get('short_desc', 'no description'),
            'group_id'    => $request->get('group_id'),
            'campaign_id' => $request->get('campaign_id')
        ]);

        return $this->response->item($plan, new RoomingPlanTransformer); 
    }

    /**
     * Update an existing plan.
     * 
     * @param  RoomingPlanRequest $request
     * @param  String             $id     
     * @return Dingo\Api\Http\Response
     */
    public function update(RoomingPlanRequest $request, $id)
    {
        $plan = $this->plan->update([
            'name'        => $request->get('name'),
            'short_desc'  => $request->get('short_desc'),
            'group_id'    => $request->get('group_id'),
            'campaign_id' => $request->get('campaign_id'),
            'locked'      => $request->get('locked')
        ], $id);

        return $this->response->item($plan, new RoomingPlanTransformer);
    }

    /**
     * Delete a plan.
     * 
     * @param  String $id
     * @return Dingo\Api\Http\Response
     */
    public function destroy($id)
    {
        $this->plan->delete($id);

        return $this->response->noContent();
    }

    public function export(ExportRequest $request)
    {
        $this->dispatch(new ExportRoomingPlans( array_filter($request->all()) ));

        return $this->response()->created(null, [
            'message' => 'Report is being generated and will be available shortly.'
        ]);
    }
}
