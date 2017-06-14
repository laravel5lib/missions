<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\TeamSquad;
use League\Fractal\ParamBag;
use App\Models\v1\TeamMember;
use App\Models\v1\Reservation;
use League\Fractal\TransformerAbstract;

class TeamSquadTransformer extends TransformerAbstract 
{
    private $validParams = ['noRoom', 'hasRoom'];

    /**
     * List of resources available to include
     *
     * @var array
     */
    protected $availableIncludes = ['members', 'team'];

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

    public function includeTeam(TeamSquad $squad)
    {
        $team = $squad->team;

        return $this->item($team, new TeamTransformer);
    }

    /**
     * Include Members
     *
     * @param TeamSquad $squad
     * @return \League\Fractal\Resource\Collection
     */
    public function includeMembers(TeamSquad $squad, ParamBag $params = null)
    {
        // check for optional parameters :param(value1|value2)
        if ( ! is_null($params)) {
            
            $filters = [];

            // loop through parameters
            foreach($params as $key => $value)
            {   
                if (isset($value[1])) {
                    // if a second value is passed
                    $filters[$key] = $value[0] . '|' . $value[1];
                } else {
                    // if only one value is passed
                    $filters[$key] = $value[0];
                }
            }

            // get filtered members
            $members = $squad->members()->filter($filters)->get();
        
        } else {
            // get members without filter parameters
            $members = $squad->members;
        }

        return $this->collection($members, new SquadMemberTransformer);
    }
}