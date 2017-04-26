<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\Reservation;
use App\Models\v1\TeamMember;
use App\Models\v1\TeamSquad;
use League\Fractal\TransformerAbstract;

class TeamSquadTransformer extends TransformerAbstract {

    /**
     * List of resources available to include
     *
     * @var array
     */
    protected $availableIncludes = ['members'];

    /**
     * Transform the object into a basic array
     *
     * @param TeamSquad $squad
     * @return array
     */
    public function transform(TeamSquad $squad)
    {
        return [
            'id'            => $squad->id,
            'team_id'       => $squad->team_id,
            'callsign'      => $squad->callsign,
            'members_count' => $squad->members_count,
            'links'         => [
                [
                    'rel' => 'self',
                    'uri' => 'api/teams/' . $squad->team_id . '/squads/' . $squad->id
                ]
            ]
        ];
    }

    /**
     * Include Members
     *
     * @param TeamSquad $squad
     * @return \League\Fractal\Resource\Collection
     */
    public function includeMembers(TeamSquad $squad)
    {
        $members = $squad->members;

        return $this->collection($members, new SquadMemberTransformer);
    }
}