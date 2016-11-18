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
        'costs'
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
     * Include Costs
     *
     * @param ProjectInitiative $initiative
     * @param ParamBag $params
     * @return Fractal\Resource\Collection
     */
    public function includeCosts(ProjectInitiative $initiative, ParamBag $params = null)
    {

        // Optional params validation
        if ( ! is_null($params)) {
            $this->validateParams($params);

            $costs = [];

            if (in_array('active', $params->get('status')))
            {
                $active = $initiative->activeCosts;

                $maxDate = $active->where('type', 'incremental')->max('active_at');

                $costs = $active->reject(function ($value, $key) use($maxDate) {
                    return $value->type == 'incremental' && $value->active_at < $maxDate;
                });
            }

        } else {
            $costs = $initiative->costs;
        }

        return $this->collection($costs, new CostTransformer);
    }

    private function validateParams($params)
    {
        $usedParams = array_keys(iterator_to_array($params));
        if ($invalidParams = array_diff($usedParams, $this->validParams)) {
            throw new \Exception(sprintf(
                'Invalid param(s): "%s". Valid param(s): "%s"',
                implode(',', $usedParams),
                implode(',', $this->validParams)
            ));
        }
    }
}