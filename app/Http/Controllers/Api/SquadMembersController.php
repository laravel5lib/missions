<?php

namespace App\Http\Controllers\Api;

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
        
        $squad->members()->attach($members);

        return $this->response->collection($squad->members, new SquadMemberTransformer);
    }


    public function update(SquadMemberRequest $request, $squadId, $memberId)
    {
        $squad = $this->squad->findOrFail($squadId);

        $squad->members()->updateExistingPivot($memberId, ['leader' => $request->get('leader')]);

        $member = $squad->members()->findOrFail($memberId);

        return $this->response->item($member, new SquadMemberTransformer);
    }

    public function destroy($squadId, $memberId)
    {
        $squad = $this->squad->findOrFail($squadId);

        $squad->members()->detach($memberId);

        return $this->response->noContent();
    }
}
