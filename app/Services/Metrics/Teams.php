<?php
namespace App\Services\Metrics;

use App\Models\v1\Team;
use App\Models\v1\TeamType;

class Teams
{
    protected $team;
    protected $type;

    function __construct(Team $team, TeamType $type)
    {
        $this->team = $team;
        $this->type = $type;
    }

    public function filter(array $filters)
    {
        $this->team->filter($filters);

        return $this;
    }

    public function totalsByType()
    {
        $totals = $this->type->all()->keyBy('name')->map(function($type) {
            return [
                'teams' => $this->team->whereTypeId($type->id)->count(),
                'members' => $this->team
                                  ->whereTypeId($type->id)
                                  ->with('squads.members')
                                  ->get()
                                  ->pluck('squads')
                                  ->flatten()
                                  ->pluck('members')
                                  ->count()
            ];
        })->all();

        return $totals;
    }

    public function totalsByTeams()
    {
        return $this->team->with('squads.members')->get()->keyBy('callsign')->map(function($team) {
            return [
                'groups' => $team->squads->count(),
                'members' => $team->squads->pluck('members')->count()
            ];
        })->all();
    }

    public function totalMembers()
    {
       return $this->team
                   ->with('squads.members')
                   ->get()
                   ->pluck('squads')
                   ->flatten()
                   ->pluck('members')
                   ->count();
    }

    public function total()
    {
        return $this->team->count();
    }
}