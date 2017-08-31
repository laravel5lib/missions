<?php

namespace App\Http\Controllers\Api\Teams;

use App\Http\Requests;
use App\Models\v1\TeamSquad;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\SquadMemberRequest;
use App\Http\Transformers\v1\SquadMemberTransformer;

class SquadMembersController extends Controller
{
    private $squad;

    function __construct(TeamSquad $squad)
    {
        $this->squad = $squad;
    }

    /**
     * Get all squad members.
     *
     * @param  Request $request
     * @param  String  $squadId
     * @return Response
     */
    public function index(Request $request, $squadId)
    {
        $members = $this->squad
            ->findOrFail($squadId)
            ->members()
            ->filter($request->all())
            ->get();

        return $this->response->collection($members, new SquadMemberTransformer);
    }

    /**
     * Get a specific squad member.
     *
     * @param  String $squadId
     * @param  String $memberId
     * @return Response
     */
    public function show($squadId, $memberId)
    {
        $member = $this->squad->findOrFail($squadId)->members()->findOrFail($memberId);

        return $this->response->item($member, new SquadMemberTransformer);
    }

    /**
     * Add one or many members to squad.
     *
     * @param  SquadMemberRequest $request
     * @param  String             $squadId
     * @return Response
     */
    public function store(SquadMemberRequest $request, $squadId)
    {
        $squad = $this->squad->findOrFail($squadId);

        $members = [ $request->get('id') => ['leader' => $request->get('leader', false)] ];

        if ($request->has('members')) {
            $members = collect($request->json('members'))->keyBy('id')->map(function ($member) {
                return ['leader' => collect($member)->get('leader', false)];
            })->all();
        }

        $squad->validateMembers($members)->validate();
        
        $squad->members()->attach($members);

        $members = $squad->members()->get();

        // We need to associate any new groups from added members with the team
        foreach ($members as $member) {
            $squad->team->groups()->sync(['teamable_id' => $member->trip->group_id], false);
        }

        return $this->response->collection($members, new SquadMemberTransformer);
    }


    public function update(SquadMemberRequest $request, $squadId, $memberId)
    {
        $squad = $this->squad->findOrFail($squadId);

        $member = $squad->members()->findOrFail($memberId);

        $squad->members()
              ->updateExistingPivot($memberId, [
                    'leader' => $request->get('leader', $member->pivot->leader),
                    'team_squad_id' => $request->get('team_squad_id', $squadId)
                ]);

        $member = $this->squad
                       ->findOrFail($request->get('team_squad_id'))
                       ->members()
                       ->findOrFail($memberId);

        return $this->response->item($member, new SquadMemberTransformer);
    }

    public function destroy($squadId, $memberId)
    {
        $squad = $this->squad->findOrFail($squadId);

        // We need to remove any groups from the team that don't have members
        if ($squad->isLastMemberOfTravelGroup($memberId)) {
            $member = $squad->members()->findOrFail($memberId);

            $squad->team->groups()->detach($member->trip->group_id);
        }

        $squad->members()->detach($memberId);

        return $this->response->noContent();
    }
}
