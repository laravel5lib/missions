<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Models\v1\Activity;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\ActivityRequest;
use App\Http\Transformers\v1\ActivityTransformer;

class ActivitiesController extends Controller
{
    private $activity;

    function __construct(Activity $activity)
    {
        $this->activity = $activity;
    }

    /**
     * Display a listing of the resource.
     *
     * @param $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $activities = $this->activity
                           ->with('type')
                           ->filter($request->all())
                           ->paginate($request->get('per_page', 10));

        return $this->response->paginator($activities, new ActivityTransformer);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ActivityRequest $request)
    {
        $activity = $this->activity->firstOrCreate([
            'name' => $request->json('name'),
            'description' => $request->json('description'),
            'occurred_at' => $request->json('occurred_at'),
            'participant_id' => $request->json('participant_id'),
            'participant_type' => $request->json('participant_type'),
            'activity_type_id' => $request->json('activity_type_id')
        ]);

        if ($request->json('itinerary_id')) {
            $activity->itineraries()->sync([$request->json('itinerary_id')], false);
        }

        if ($request->json('transport_id')) {
            $activity->transports()->sync([$request->json('transport_id')], false);
        }

        if ($request->json('hub_id')) {
            $activity->hubs()->sync([$request->json('hub_id')], false);
        }

        return $this->response->item($activity, new ActivityTransformer);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $activity = $this->activity->findOrFail($id);

        return $this->response->item($activity, new ActivityTransformer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $request
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ActivityRequest $request, $id)
    {
        $activity = $this->activity->findOrFail($id);

        $activity->update([
            'name' => $request->json('name', $activity->name),
            'description' => $request->json('description', $activity->description),
            'occurred_at' => $request->json('occurred_at', $activity->occurred_at),
            'participant_id' => $request->json('participant_id', $activity->participant_id),
            'participant_type' => $request->json('participant_type', $activity->participant_type),
            'activity_type_id' => $request->json('activity_type_id', $activity->activity_type_id)
        ]);

        if ($request->json('itinerary_id')) {
            $activity->itineraries()->sync([$request->json('itinerary_id')], false);
        }

        if ($request->json('transport_id')) {
            $activity->transports()->sync([$request->json('transport_id')], false);
        }

        if ($request->json('hub_id')) {
            $activity->hubs()->sync([$request->json('hub_id')], false);
        }

        return $this->response->item($activity, new ActivityTransformer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         $activity = $this->activity->findOrFail($id);

         $activity->delete();

         return $this->response->noContent();
    }
}
