<?php

namespace App\Http\Controllers\Api\Teams;

use App\Http\Requests;
use App\Models\v1\Team;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\TeamSquadRequest;
use App\Http\Transformers\v1\TeamSquadTransformer;

class TeamSquadsController extends Controller
{
    private $team;

    function __construct(Team $team)
    {
        $this->team = $team;
    }

    /**
     * Get all squads in a team.
     * 
     * @param  Request $request
     * @param  String  $teamId
     * @return Response
     */
    public function index(Request $request, $teamId)
    {
        $squads = $this->team
            ->findOrFail($teamId)
            ->squads()
            ->filter($request->all())
            ->withCount('members')
            ->get();

        return $this->response->collection($squads, new TeamSquadTransformer);
    }

    /**
     * Get specific team squad
     * 
     * @param  String $teamId
     * @param  String $squadId
     * @return Response
     */
    public function show($teamId, $squadId)
    {
        $squad = $this->team
            ->findOrFail($teamId)
            ->squads()
            ->withCount('members')
            ->findOrFail($squadId);

        return $this->response->item($squad, new TeamSquadTransformer);
    }

    /**
     * Create a new squad in the team.
     * 
     * @param  TeamSquadRequest $request
     * @param  String           $teamId
     * @return Response
     */
    public function store(TeamSquadRequest $request, $teamId)
    {
        $team = $this->team->findOrFail($teamId);

        $team->validateSquads()->validate();

        $squad = $team->squads()->create([
            'callsign' => $request->get('callsign')
        ]);

        return $this->response->item($squad, new TeamSquadTransformer);
    }

    public function update(TeamSquadRequest $request, $teamId, $squadId)
    {
        $squad = $this->team
            ->findOrFail($teamId)
            ->squads()
            ->findOrFail($squadId);

        $squad->update([
            'callsign' => $request->get('callsign', $squad->callsign),
            'team_id' => $request->get('team_id', $squad->team_id)
        ]);

        return $this->response->item($squad, new TeamSquadTransformer);
    }

    public function destroy($teamId, $squadId)
    {
        $team = $this->team->findOrFail($teamId);

        $squad = $team->squads()->findOrFail($squadId);

        $team->validateSquads()
             ->assertMeetsMinimumSquads()
             ->done();

        $squad->delete();

        return $this->response->noContent();
    }
}
