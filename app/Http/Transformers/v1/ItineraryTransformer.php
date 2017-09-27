<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\Itinerary;
use League\Fractal\TransformerAbstract;

class ItineraryTransformer extends TransformerAbstract
{


    /**
     * List of resources available to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'activities'
    ];

    public function transform(Itinerary $itinerary)
    {
        return [
            'id'             => $itinerary->id,
            'name'           => $itinerary->name,
            'curator_id'     => $itinerary->curator_id,
            'curator_type'   => $itinerary->curator_type,
            'activities'     => $itinerary->activities_count ?: $itinerary->activities()->count(),
            'updated_at'     => $itinerary->updated_at->toDateTimeString(),
            'created_at'     => $itinerary->created_at->toDateTimeString(),
            'deleted_at'     => $itinerary->deleted_at ? $itinerary->deleted_at->toDateTimeString() : null,
            'links'          => [
                [
                    'rel' => 'self',
                    'uri' => '/api/itineraries/' . $itinerary->id,
                ]
            ]
        ];
    }

    public function includeActivities(Itinerary $itinerary)
    {
        $activities = $itinerary->activities;

        return $this->collection($activities, new ActivityTransformer);
    }
}
