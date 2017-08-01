<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\Team;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;

class TeamTransformer extends TransformerAbstract
{

    /**
     * List of resources available to include
     *
     * @var array
     */
    protected $availableIncludes = ['squads', 'groups', 'campaigns', 'type'];

    /**
     * Transform the object into a basic array
     *
     * @param Team $team
     * @return array
     */
    public function transform(Team $team)
    {
        $team->load('squads.members');

        return [
            'id'           => $team->id,
            'callsign'     => $team->callsign,
            'type_id'      => $team->type_id,
            'locked'       => (boolean) $team->locked,
            'groups_count' => $team->groups_count,
            'squads_count' => $team->squads_count,
            'members_count'=> $team->squads->lists('members')->collapse()->count(),
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

    /**
     * Include team squads.
     *
     * @param  Team   $team
     * @return Collection
     */
    public function includeSquads(Team $team)
    {
        $squads = $team->squads;

        return $this->collection($squads, new TeamSquadTransformer);
    }

    /**
     * Include groups assocated with the team.
     *
     * @param  Team   $team
     * @return Collection
     */
    public function includeGroups(Team $team)
    {
        $groups = $team->groups;

        return $this->collection($groups, new GroupTransformer);
    }

    /**
     * Include campaigns associated with the team
     *
     * @param  Team   $team
     * @return Collection
     */
    public function includeCampaigns(Team $team)
    {
        $campaigns = $team->campaigns;

        return $this->collection($campaigns, new CampaignTransformer);
    }

    /**
     * Include type associated with the team
     *
     * @param  Team   $team
     * @return Item
     */
    public function includeType(Team $team)
    {
        $type = $team->type;

        return $this->item($type, new TeamTypeTransformer);
    }
}
