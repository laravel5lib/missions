<?php

namespace App\Http\Controllers\Api\Teams;

use App\Http\Requests;
use App\Models\v1\Team;
use Illuminate\Http\Request;
use App\Services\Teams\ManageTeams;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\TeamableRequest;
use App\Http\Transformers\v1\TeamTransformer;

class TeamablesController extends Controller
{
    private $team;

    function __construct(Team $team)
    {
        $this->team = $team;
    }

    public function index($teamableType, $teamableId, Request $request)
    {
        $request->merge([$teamableType => $teamableId]);

        $teams = $this->team
                      ->filter($request->all())
                      ->paginate($request->get('per_page'));

        return $this->response->paginator($teams, new TeamTransformer);
    }

    public function store($teamableType, $teamableId, TeamableRequest $request)
    {
        (new ManageTeams($teamableType, $teamableId))->add($request->get('ids'));

        return $this->response->created();
    }

    public function destroy($teamableType, $teamableId, $id)
    {
        (new ManageTeams($teamableType, $teamableId))->remove($id);

        return $this->response->noContent();
    }
}
