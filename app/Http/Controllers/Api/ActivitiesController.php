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
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $activities = $this->activity
                           ->filter($request->all())
                           ->paginate($request->get('per_page', 10));

        return $this->response->paginator($activities, new ActivityTransformer);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ActivityRequest $request)
    {
        $activity = $this->activity->create([
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'occurred_at' => $request->get('occurred_at'),
            'participant_id' => $request->get('participant_id'),
            'participant_type' => $request->get('participant_type')
        ]);

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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ActivityRequest $request, $id)
    {
        $activity = $this->activity->findOrFail($id);

        $activity->update([
            'name' => $request->get('name', $activity->name),
            'description' => $request->get('description', $activity->description),
            'occurred_at' => $request->get('occurred_at', $activity->occurred_at),
            'participant_id' => $request->get('participant_id', $activity->participant_id),
            'participant_type' => $request->get('participant_type', $activity->participant_type)
        ]);

        return $this->response->item($activity, new ActivityTransformer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         $activity = $this->activity->findOrFail($id);

         $activity->delete();

         return $this->response->noContent();
    }
}
