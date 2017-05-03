<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Models\v1\Team;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\TeamableRequest;

class TeamablesController extends Controller
{
    private $team;

    function __construct(Team $team)
    {
        $this->team = $team;
    }

    public function store(TeamableRequest $request, $id, $type)
    {
        $this->team
             ->findOrFail($id)
             ->{$type}()
             ->attach($request->get('ids'));

        return $this->response->created();
    }

    public function destroy(TeamableRequest $request, $id, $type, $teamableId = null)
    {
        $this->team
             ->findOrFail($id)
             ->{$type}()
             ->detach($teamableId ?: $request->get('ids'));

        return $this->response->noContent();
    }
}
