<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\TeamType;
use League\Fractal\TransformerAbstract;

class TeamTypeTransformer extends TransformerAbstract {

    /**
     * Transform the object into a basic array
     *
     * @param TeamType $type
     * @return array
     */
    public function transform(TeamType $type)
    {
        return [
            'id'           => $type->id,
            'campaign_id'  => $type->campaign_id,
            'name'         => $type->name,
            'rules'        => $type->rules,
            'created_at'   => $type->created_at->toDateTimeString(),
            'updated_at'   => $type->updated_at->toDateTimeString(),
            'links'        => [
                [
                    'rel' => 'self',
                    'uri' => 'api/teams/types/' . $type->id,
                ]
            ]
        ];
    }
}