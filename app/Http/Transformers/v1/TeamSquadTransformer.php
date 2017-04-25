<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\TeamSquad;
use League\Fractal\TransformerAbstract;

class TeamSquadTransformer extends TransformerAbstract {

    /**
     * List of resources available to include
     *
     * @var array
     */
    protected $availableIncludes = [];

    /**
     * Transform the object into a basic array
     *
     * @param TeamSquad $squad
     * @return array
     */
    public function transform(TeamSquad $squad)
    {
        return [
            'id'           => $squad->id,
            'team_id'      => $squad->team_id,
            'callsign'     => $squad->callsign,
            'links'        => [
                [
                    'rel' => 'self',
                    'uri' => 'api/teams/' . $squad->team_id . '/squads/' . $squad->id
                ]
            ]
        ];
    }
}