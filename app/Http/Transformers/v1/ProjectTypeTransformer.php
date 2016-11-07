<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\ProjectType;
use League\Fractal;
use League\Fractal\ParamBag;

class ProjectTypeTransformer extends Fractal\TransformerAbstract
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
     * @param ProjectType $type
     * @return array
     */
    public function transform(ProjectType $type)
    {
        $type->load('image');

        return [
            'id'             => $type->id,
            'name'           => $type->name,
            'country'        => [
                'code' => $type->country_code,
                'name' => country($type->country_code)
            ],
            'short_desc'     => $type->short_desc,
//            'image'         => $type->image ? image($type->image->source) : null,
            'projects_count' => $type->projects_count,
            'created_at'     => $type->created_at->toDateTimeString(),
            'updated_at'     => $type->updated_at->toDateTimeString(),
            'links'          => [
                [
                    'rel' => 'self',
                    'uri' => url('/api/types/' . $type->id),
                ]
            ]
        ];
    }

    /**
     * Include Costs
     *
     * @param ProjectType $type
     * @param ParamBag $params
     * @return Fractal\Resource\Collection
     */
    public function includeCosts(ProjectType $type, ParamBag $params = null)
    {

        // Optional params validation
        if ( ! is_null($params)) {
            $this->validateParams($params);

            $costs = [];

            if (in_array('active', $params->get('status')))
            {
                $active = $type->activeCosts;

                $maxDate = $active->where('type', 'incremental')->max('active_at');

                $costs = $active->reject(function ($value, $key) use($maxDate) {
                    return $value->type == 'incremental' && $value->active_at < $maxDate;
                });
            }

        } else {
            $costs = $type->costs;
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