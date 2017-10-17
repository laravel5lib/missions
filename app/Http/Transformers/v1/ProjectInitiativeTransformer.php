<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\ProjectInitiative;
use League\Fractal;
use League\Fractal\ParamBag;

class ProjectInitiativeTransformer extends Fractal\TransformerAbstract
{

    private $validParams = ['status'];

    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $availableIncludes = [
        'cause'
    ];

    /**
     * Turn this item object into a generic array
     *
     * @param ProjectInitiative $initiative
     * @return array
     */
    public function transform(ProjectInitiative $initiative)
    {
        return [
            'id'             => $initiative->id,
            'type'           => $initiative->type,
            'country'        => [
                'code' => $initiative->country_code,
                'name' => country($initiative->country_code)
            ],
            'short_desc'     => $initiative->short_desc,
            'projects_count' => $initiative->projects_count,
            'started_at'     => $initiative->started_at->toDateTimeString(),
            'ended_at'       => $initiative->ended_at->toDateTimeString(),
            'created_at'     => $initiative->created_at->toDateTimeString(),
            'updated_at'     => $initiative->updated_at->toDateTimeString(),
            'links'          => [
                [
                    'rel' => 'self',
                    'uri' => url('/api/initiatives/' . $initiative->id),
                ]
            ]
        ];
    }

    /**
     * Include Cause.
     *
     * @param ProjectInitiative $initiative
     * @return Fractal\Resource\Item
     */
    public function includeCause(ProjectInitiative $initiative)
    {
        $cause = $initiative->cause;

        return $this->item($cause, new ProjectCauseTransformer);
    }
}
