<?php

namespace App\Http\Transformers\v1;
use App\Models\v1\Itinerary;
use League\Fractal\TransformerAbstract;

class ItineraryTransformer extends TransformerAbstract
{
    public function transform(Itinerary $itinerary)
    {
        return [
            'id'             => $itinerary->id,
            'name'           => $itinerary->name,
            'itinerant_id'   => $itinerary->itinerant_id,
            'itinerant_type' => $itinerary->itinerant_type,
            'items'          => $itinerary->items_count,
            'updated_at'     => $itinerary->updated_at->toDateTimeString(),
            'created_at'     => $itinerary->created_at->toDateTimeString(),
            'deleted_at'     => $itinerary->deleted_at ? $itinerary->deleted_at->toDateTimeString() : null
        ];
    }
}