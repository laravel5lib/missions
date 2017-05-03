<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\Airline;
use League\Fractal\TransformerAbstract;

class AirlineTransformer extends TransformerAbstract
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
     * @param Airline $airline
     * @return array
     */
    public function transform(Airline $airline)
    {
        return $airline->toArray();
    }
}