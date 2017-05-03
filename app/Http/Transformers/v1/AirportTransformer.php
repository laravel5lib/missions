<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\Airport;
use League\Fractal\TransformerAbstract;

class AirportTransformer extends TransformerAbstract
{
    /**
     * List of resources available to include
     *
     * @var array
     */
    protected $availableIncludes = [];

    /**
     * Transform the object into a basic array
     *
     * @param Airport $airport
     * @return array
     */
    public function transform(Airport $airport)
    {
        return $airport->toArray();
    }
}