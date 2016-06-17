<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\TeamMemberRequest;
use App\Http\Transformers\v1\TeamMemberTransformer;
use App\Models\v1\TeamMember;
use Illuminate\Http\Request;

use App\Http\Requests;

class TeamMembersController extends Controller
{

    /**
     * @var TeamMember
     */
    private $member;

    /**
     * TeamMembersController constructor.
     * @param TeamMember $member
     */
    public function __construct(TeamMember $member)
    {
        $this->member = $member;

        $this->middleware('api.auth');
//        $this->middleware('jwt.refresh');
    }

    /**
     * Return a paginated list of team members
     *
     * @param $team_id
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index($team_id, Request $request)
    {
        $members = $this->member->where('team_id', $team_id)
                                ->filter($request->all())
                                ->paginate($request->get('per_page', 10));

        return $this->response->paginator($members, new TeamMemberTransformer);
    }

    /**
     * Create a new team member
     *
     * @param $team_id
     * @param TeamMemberRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store($team_id, TeamMemberRequest $request)
    {
        $member = new TeamMember($request->all());
        $member->team_id = $team_id;
        $member->save();

        return $this->response->item($member, new TeamMemberTransformer);
    }

    /**
     * Return a specific team member by id
     *
     * @param $team_id
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($team_id, $id)
    {
        $member = $this->member->where('team_id', $team_id)->findOrFail($id);

        if ( ! $member->team->isPublished() and  ! $this->auth->user()->isAdmin())
        {
            abort(403);
        }

        return $this->response->item($member, new TeamMemberTransformer);
    }

    /**
     * Update a specific team member by id
     *
     * @param $team_id
     * @param $id
     * @param TeamMemberRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function update($team_id, $id, TeamMemberRequest $request)
    {
        $member = $this->member->where('team_id', $team_id)->findOrFail($id);

        $member->update($request->all());

        return $this->response->item($member, new TeamMemberTransformer);
    }

    /**
     * Delete a specific team member by id
     *
     * @param $team_id
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($team_id, $id)
    {
        $member = $this->member->where('team_id', $team_id)->findOrFail($id);

        $member->delete();

        return $this->response->noContent();
    }

}
