<?php

namespace App\Jobs;

use App\Models\v1\Trip;

class ExportTrips extends Exporter
{
    public function data($request)
    {
        return Trip::filter($request)
            ->with(['group', 'campaign', 'rep'])
            ->get();
    }

    public function columns($trip)
    {
        $teamRoles = collect($trip->team_roles)->map(function ($code) {
            return teamRole($code);
        })->implode(', ');

        return [
            'id'              => $trip->id,
            'group_name'      => $trip->group_name,
            'campaign_name'   => $trip->campaign_name,
            'rep_name'        => $trip->rep ? $trip->rep->name : null,
            'spots'           => $trip->spots,
            'status'          => $trip->status,
            'companion_limit' => $trip->companion_limit,
            'country_code'    => $trip->country_code,
            'country'         => country($trip->country_code),
            'type'            => $trip->type,
            'difficulty'      => $trip->difficulty,
            'started_at'      => $trip->started_at->toDateString(),
            'ended_at'        => $trip->ended_at->toDateString(),
            'todos'           => implode(', ', $trip->todos),
            'prospects'       => implode(', ', $trip->prospects),
            'team_roles'      => $teamRoles,
            'visibility'      => $trip->public ? 'public' : 'private',
            'published_at'    => $trip->published_at ? $trip->published_at->toDateTimeString() : null,
            'closed_at'       => $trip->closed_at->toDateTimeString(),
            'created_at'      => $trip->created_at->toDateTimeString(),
            'updated_at'      => $trip->updated_at->toDateTimeString(),
        ];
    }
}
