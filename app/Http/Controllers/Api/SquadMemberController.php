<?php

namespace App\Http\Controllers\Api;

use App\Models\v1\Squad;
use App\Models\v1\Region;
use Illuminate\Http\Request;
use App\Models\v1\SquadMember;
use App\Http\Controllers\Controller;
use App\Http\Resources\SquadMemberResource;
use App\Http\Requests\v1\SquadMemberRequest;

class SquadMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $members = SquadMember::buildQuery()->paginate(request()->get('per_page', 25));
        
        return SquadMemberResource::collection($members);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SquadMemberRequest $request)
    {   
        Squad::whereUuid($request->input('squad_id'))
            ->firstOrFail()
            ->updateOrAddMembers(
                $request->input('reservation_ids'), 
                $request->input('group')
            );

        return response()->json(['message' => 'Squad member added to squad.'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $uuid
     * @return \Illuminate\Http\Response
     */
    public function show($uuid)
    {
        $member = SquadMember::buildQuery()->where('squad_members.uuid', $uuid)->firstOrFail();

        return new SquadMemberResource($member);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $ids)
    {
        $uuids = explode(',', $ids);

        SquadMember::whereIn('uuid', $uuids)->get()->each( function ($member) use ($request) {
            $member->group = $request->input('group', $member->group);
            $member->squad_id = $request->input('squad_id', $member->squad_id);
            $member->save();
        });

        return response()->json(['message' => 'Squad members updated.'], 200);
    }

    /**
     * Remove the specified resource(s) from storage.
     *
     * @param  string  $ids
     * @return \Illuminate\Http\Response
     */
    public function destroy($ids)
    {
        $uuids = explode(',', $ids);

        SquadMember::whereIn('uuid', $uuids)->delete();

        return response()->json([], 204);
    }
}
