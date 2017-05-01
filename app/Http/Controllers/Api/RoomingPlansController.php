<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Rooming\RoomingPlan;

class RoomingPlansController extends Controller
{
    protected $plan;

    function __construct(RoomingPlan $plan)
    {
        $this->plan = $plan;
    }

    public function index(Request $request)
    {
        $plans = $this->plan->get();

        return $plans;

        // return $this->response->paginator($plans, new RoomingPlanTransformer);
    }

    public function show($id, Request $request)
    {
        $plan = $this->plan->find($id)->get();

        return $plan;
    }
}
