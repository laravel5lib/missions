<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\ProjectInitiative;
use League\Fractal;

class ProjectInitiativeTransformer extends Fractal\TransformerAbstract
{
    /**
     * List of resources possible to include
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
            'id'        => $initiative->id,
            'name'      => $initiative->name,
            'country'   => [
                'name' => country($initiative->country_code),
                'code' => strtolower($initiative->country_code)
            ],
            'active'    => (boolean) $initiative->active,
            'started_at'    => $initiative->started_at->toDateTimeString(),
            'ended_at'      => $initiative->ended_at ? $initiative->ended_at->toDateTimeString() : null,
            'created_at'    => $initiative->created_at->toDateTimeString(),
            'updated_at'    => $initiative->updated_at->toDateTimeString(),
            'links'     => [
                [
                    'rel' => 'self',
                    'uri' => url('/api/causes/' . $initiative->cause->id . '/initiatives/'.$initiative->id),
                ]
            ],
        ];
    }

    /**
     * Include Cause
     * @param ProjectInitiative $initiative
     * @return Fractal\Resource\Item
     */
    public function includeCause(ProjectInitiative $initiative)
    {
        $cause = $initiative->cause;

        return $this->item($cause, new CauseTransformer);
    }
}