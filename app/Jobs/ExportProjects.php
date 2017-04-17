<?php

namespace App\Jobs;

use App\Models\v1\Project;

class ExportProjects extends Exporter
{
    public function data(array $request)
    {
        return Project::filter($request)
            ->with(['initiative', 'sponsor','rep'])
            ->get();
    }

    public function columns($project)
    {
        return [
            'id'    => $project->id,
            'name'  => $project->name,
            'initiative_type' => $project->initiative->type,
            'rep_name'  => $project->rep->name,
            'sponsor_name'  => $project->sponsor->name,
            'plaque_prefix'  => $project->plaque_prefix,
            'plaque_message' => $project->plaque_message,
            'created_at' => $project->created_at->toDateTimeString(),
            'updated_at' => $project->updated_at->toDateTimeString(),
        ];
    }
}
