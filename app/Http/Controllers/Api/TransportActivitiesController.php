<?php

namespace App\Http\Controllers\api;

use App\Http\Requests;
use App\Models\v1\Transport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\ActivityRequest;
use App\Http\Transformers\v1\ActivityTransformer;

class TransportActivitiesController extends Controller
{
    function __construct(Transport $transport)
    {
        $this->transport = $transport;
    }

    public function index($transportId, Request $request)
    {
        $transport = $this->transport->findOrFail($transportId);

        $activities = $transport->activities()
                                ->filter($request->all())
                                ->paginate($request->get('per_page', 10));

        return $this->response->paginator($activities, new ActivityTransformer);
    }

    public function show($transportId, $id)
    {
        $transport = $this->transport->findOrFail($transportId);

        $activity = $transport->activities()->findOrFail($id);

        return $this->response->item($activity, new ActivityTransformer);
    }

    public function store($transportId, ActivityRequest $request)
    {
        $transport = $this->transport->findOrFail($transportId);

        $activity = $transport->activities()->create([
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'occured_at' => $request->get('occured_at')
        ]);

        return $this->response->item($activity, new ActivityTransformer);
    }

    public function delete($transportId, $id)
    {
        $transport = $this->transport->findOrFail($transportId);

        $activity = $transport->activities()->findOrFail($id);

        $activity->delete();

        return $this->response->noContent();
    }
}
