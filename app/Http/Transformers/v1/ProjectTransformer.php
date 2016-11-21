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
        'notes',
        'costs',
        'sponsor',
        'rep',
        'initiative'
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
            'id'                    => $project->id,
            'name'                  => $project->name,
            'project_initiative_id' => $project->project_initiative_id,
            'sponsor_id'            => $project->sponsor_id,
            'sponsor_type'          => $project->sponsor_type,
            'plaque_prefix'         => $project->plaque_prefix,
            'plaque_message'        => $project->plaque_message,
            'goal'                  => (int) $project->goal,
            'amount_raised'         => (int) $project->amount_raised,
            'percent_raised'        => (int) $project->precent_raised,
            'funded_at'             => $project->funded_at ? $project->funded_at->toDateTimeString() : null,
            'created_at'            => $project->created_at->toDateTimeString(),
            'updated_at'            => $project->updated_at->toDateTimeString(),
            'links'                 => [
                [
                    'rel' => 'self',
                    'uri' => url('/api/projects/' . $project->id),
                ]
            ]
        ];
    }

    /**
     * Include sponsor.
     *
     * @param Project $project
     * @return Fractal\Resource\Item
     */
    public function includeSponsor(Project $project)
    {
        return $this->item($project->sponsor, new UserTransformer);
    }

    /**
     * Include initiative.
     *
     * @param Project $project
     * @return Fractal\Resource\Item
     */
    public function includeInitiative(Project $project)
    {
        $initiative = $project->initiative;

        return $this->item($initiative, new ProjectInitiativeTransformer);
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