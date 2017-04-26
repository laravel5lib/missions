<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\TeamRequest;
use App\Http\Transformers\v1\TeamTransformer;
use App\Models\v1\Team;
use Illuminate\Http\Request;

use App\Http\Requests;

class TeamsController extends Controller
{

    /**
     * @var Team
     */
    private $team;

    /**
     * TeamsController constructor.
     * @param Team $team
     */
    public function __construct(Team $team)
    {
        $this->team = $team;
    }

    /**
     * Return a paginated list of teams
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request)
    {
        $teams = $this->team
            ->filter($request->all())
            ->withCount('groups')
            ->withCount('squads')
            ->paginate($request->get('per_page', 10));

        return $this->response->paginator($teams, new TeamTransformer);
    }

    /**
     * Create a new team
     *
     * @param TeamRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(TeamRequest $request)
    {
        $team = $this->team->create([
            'callsign' => $request->get('callsign'),
            'locked' => $request->get('locked', false)
        ]);

        if ($request->has('associations')) {
            
            $team->addTeamables($request->get('associations'));
        }

        return $this->response->item($team, new TeamTransformer);
    }

    /**
     * Return a specific team by id
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($id)
    {
        $team = $this->team
            ->withCount('groups')
            ->withCount('squads')
            ->findOrFail($id);

        return $this->response->item($team, new TeamTransformer);
    }

    /**
     * Update a specific team by id
     *
     * @param $id
     * @param TeamRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function update($id, TeamRequest $request)
    {
        $team = $this->team->findOrFail($id);

        $team->update([
            'callsign' => $request->get('callsign', $team->callsign),
            'locked' => $request->get('locked', $team->locked)
        ]);

        return $this->response->item($team, new TeamTransformer);
    }

    /**
     * Delete a specific team by id
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($id)
    {
        $team = $this->team->findOrFail($id);

        $team->delete();

        return $this->response->noContent();
    }
}
