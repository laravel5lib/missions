<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\Team;
use League\Fractal\TransformerAbstract;

class TeamTransformer extends TransformerAbstract {

    /**
     * List of resources available to include
     *
     * @var array
     */
    protected $availableIncludes = [];

    /**
     * Transform the object into a basic array
     *
     * @param Team $team
     * @return array
     */
    public function transform(Team $team)
    {
        return [
            'id'           => $team->id,
            'callsign'     => $team->callsign,
            'created_at'   => $team->created_at->toDateTimeString(),
            'updated_at'   => $team->updated_at->toDateTimeString(),
            'deleted_at'   => $team->deleted_at ? $team->deleted_at->toDateTimeString() : null,
            'links'        => [
                [
                    'rel' => 'self',
                    'uri' => 'api/teams/' . $team->id,
                ]
            ]
        ];
    }
}