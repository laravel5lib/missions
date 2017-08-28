<?php

namespace App\Http\Controllers\api;

use App\Http\Requests;
use App\Models\v1\Hub;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\ActivityRequest;
use App\Http\Transformers\v1\ActivityTransformer;

class HubActivitiesController extends Controller
{

    private $hub;

    function __construct(Hub $hub)
    {
        $this->hub = $hub;
    }

    public function index($hubId, Request $request)
    {
        $hub = $this->hub->findOrFail($hubId);

        $activities = $hub->activities()
                                ->filter($request->all())
                                ->paginate($request->get('per_page', 10));

        return $this->response->paginator($activities, new ActivityTransformer);
    }

    public function show($hubId, $id)
    {
        $hub = $this->hub->findOrFail($hubId);

        $activity = $hub->activities()->findOrFail($id);

        return $this->response->item($activity, new ActivityTransformer);
    }

    public function store($hubId, Request $request)
    {
        $hub = $this->hub->findOrFail($hubId);

        $hub->activities()->sync($request->json('activities'), false);

        $activities = $hub->activities;

        return $this->response->collection($activities, new ActivityTransformer);
    }

    public function destroy($hubId, $id)
    {
        $hub = $this->hub->findOrFail($hubId);

        $hub->activities()->detach($id);

        return $this->response->noContent();
    }
}
