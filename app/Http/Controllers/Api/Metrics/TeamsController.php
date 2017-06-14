<?php

namespace App\Http\Controllers\Api\Metrics;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Services\Metrics\Teams;
use App\Http\Controllers\Controller;
use App\Http\Transformers\v1\Metrics\TeamMetricsTransformer;

class TeamsController extends Controller
{
    public function index(Request $request)
    {
        $teams = app(Teams::class)->filter($request->all());

        $metrics = collect([
            'total_teams' => $teams->total(),
            'total_members' => $teams->totalMembers(),
            'types' => $teams->totalsByType(),
            'teams' => $teams->totalsByTeams()
        ]);

        return $this->response->item($metrics, new TeamMetricsTransformer);
    }
}
