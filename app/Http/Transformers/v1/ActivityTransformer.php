<?php

namespace App\Http\Transformers\v1;
use App\Models\v1\Activity;
use League\Fractal\TransformerAbstract;

class ActivityTransformer extends TransformerAbstract
{
    /**
     * List of resources available to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'hubs', 'transports'
    ];

    public function transform(Activity $activity)
    {
        return [
            'id'                => $activity->id,
            'name'              => $activity->name,
            'description'       => $activity->description,
            'participant_id'    => $activity->participant_id,
            'participant_type'  => $activity->participant_type,
            'occurred_at'       => $activity->occurred_at->toDateTimeString(),
            'created_at'        => $activity->created_at->toDateTimeString(),
            'updated_at'        => $activity->updated_at->toDateTimeString(),
            'deleted_at'        => $activity->deleted_at ? 
                                        $activity->deleted_at->toDateTimeString() : 
                                        null,
        ];
    }

    public function includeHubs(Activity $activity)
    {
        $hubs = $activity->hubs;

        return $this->collection($hubs, new HubTransformer);
    }

    public function includeTransports(Activity $activity)
    {
        $transports = $activity->transports;

        return $this->collection($transports, new TransportTransformer);
    }
}