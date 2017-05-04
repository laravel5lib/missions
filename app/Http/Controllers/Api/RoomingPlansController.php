<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Rooming\RoomingPlan;
use App\Http\Requests\v1\RoomingPlanRequest;
use App\Http\Transformers\v1\RoomingPlanTransformer;

class RoomingPlansController extends Controller
{
    protected $plan;

    function __construct(RoomingPlan $plan)
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
                      ->filter($request)
                      ->get($request->get('per_page', 10));

        return $plans;

        return $this->response->paginator($plans, new RoomingPlanTransformer);
    }

    /**
     * Get specific plan.
     *
     * @param  String  $id
     * @param  Request $request
     * @return Dingo\Api\Http\Response
     */
    public function show($id, Request $request)
    {
        $plan = $this->plan->find($id)->get();

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
        $plan = $this->plan->make($request);

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
        $plan = $this->plan->find($id)->update($request);

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
        $this->plan->find($id)->delete();

        return $this->response->noContent();
    }
}
