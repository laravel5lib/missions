<?php

namespace App\Http\Transformers\v1;
use App\Models\v1\Hub;
use League\Fractal\TransformerAbstract;

class HubTransformer extends TransformerAbstract
{
    public function transform(Hub $hub)
    {
        return [
            'id'           => $hub->id,
            'name'         => $hub->name,
            'meta'         => $hub->meta
        ];
    }
}