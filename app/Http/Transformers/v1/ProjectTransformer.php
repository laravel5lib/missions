<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\Project;
use League\Fractal;

class ProjectTransformer extends Fractal\TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'package',
        'notes',
        'costs',
        'sponsor',
        'rep'
    ];

    /**
     * Turn this item object into a generic array
     *
     * @param Project $project
     * @return array
     */
    public function transform(Project $project)
    {
        return [
            'id'                 => $project->id,
            'project_package_id' => $project->project_package_id,
            'rep_id'             => $project->rep_id ? $project->rep_id : $project->package->initiative->rep_id,
            'sponsor_id'         => $project->sponsor_id,
            'sponsor_type'       => $project->sponsor_type,
            'plaque'             => [
                'prefix'  => $project->plaque_prefix,
                'message' => $project->plaque_message
            ],
            'funded_at'          => $project->funded_at ? $project->funded_at->toDateTimeString() : null,
            'launched_at'        => $project->launched_at ? $project->launched_at->toDateTimeString() : null,
            'completed_at'       => $project->completed_at ? $project->completed_at->toDateTimeString() : null,
            'created_at'         => $project->created_at->toDateTimeString(),
            'updated_at'         => $project->updated_at->toDateTimeString(),
            'links'              => [
                [
                    'rel' => 'self',
                    'uri' => url('/api/projects/' . $project->id),
                ]
            ]
        ];
    }

    /**
     * Include package.
     *
     * @param Project $project
     * @return Fractal\Resource\Item
     */
    public function includePackage(Project $project)
    {
        return $this->item($project->package, new ProjectPackageTransformer);
    }

    /**
     * Include rep.
     *
     * @param Project $project
     * @return Fractal\Resource\Item
     */
    public function includeRep(Project $project)
    {
        return $this->item($project->rep, new UserTransformer);
    }

    /**
     * Include costs.
     *
     * @param Project $project
     * @return Fractal\Resource\Collection
     */
    public function includeCosts(Project $project)
    {
        $costs = $project->costs;

        if ( ! $costs) return null;

        return $this->collection($costs, new CostTransformer);
    }

    /**
     * Include most recent notes.
     *
     * @param Project $project
     * @return Fractal\Resource\Collection
     */
    public function includeNotes(Project $project)
    {
        $notes = $project->notes()->recent()->get();

        return $this->collection($notes, new NoteTransformer);
    }
}