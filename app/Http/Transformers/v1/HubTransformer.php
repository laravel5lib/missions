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
            'campaign_id'  => $hub->campaign->id,
            'name'         => $hub->name,
            'call_sign'    => $hub->call_sign,
            'address'      => $hub->address,
            'city'         => $hub->city,
            'state'        => $hub->state,
            'city'         => $hub->city,
            'zip'          => $hub->zip,
            'country_code' => $hub->country_code,
            'created_at'   => $hub->created_at->toDateTimeString(),
            'updated_at'   => $hub->updated_at->toDateTimeString()
        ];
    }
}