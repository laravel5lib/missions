<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\Trip;
use League\Fractal\ParamBag;
use League\Fractal\TransformerAbstract;

class TripTransformer extends TransformerAbstract
{

    private $validParams = ['status'];

    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $availableIncludes = [
        'campaign', 'group', 'deadlines', 'requireables', 'facilitators', 'rep'
    ];

    /**
     * Turn this item object into a generic array
     *
     * @param Trip $trip
     * @return array
     */
    public function transform(Trip $trip)
    {
        return [
            'id'              => $trip->id,
            'group_id'        => $trip->group_id,
            'campaign_id'     => $trip->campaign_id,
            'rep_id'          => $trip->rep_id,
            'rep'             => $trip->rep ? $trip->rep->name : null,
            'spots'           => (int) $trip->spots,
            'status'          => $trip->status,
            // 'starting_cost'   => $trip->startingCostInDollars(),
            'companion_limit' => (int) $trip->companion_limit,
            'reservations'    => (int) $trip->reservations_count,
            'country_code'    => $trip->country_code,
            'country_name'    => country($trip->country_code),
            'type'            => $trip->type,
            'difficulty'      => $trip->difficulty,
            'started_at'      => $trip->started_at->toDateString(),
            'ended_at'        => $trip->ended_at->toDateString(),
            'todos'           => $trip->todos ?: [],
            'prospects'       => $trip->prospects ?: [],
            'team_roles'      => $trip->team_roles ?: [],
            'description'     => $trip->description,
            'public'          => (boolean) $trip->public,
            'published_at'    => $trip->published_at ? $trip->published_at->toIso8601String() : null,
            'closed_at'       => $trip->closed_at ? $trip->closed_at->toIso8601String() : null,
            'created_at'      => $trip->created_at->toIso8601String(),
            'updated_at'      => $trip->updated_at->toIso8601String(),
            'links'           => [
                [
                    'rel' => 'self',
                    'uri' => '/trips/' . $trip->id,
                ]
            ],
        ];
    }

    /**
     * Include Campaign
     *
     * @param Trip $trip
     * @return \League\Fractal\Resource\Item
     */
    public function includeCampaign(Trip $trip)
    {
        $campaign = $trip->campaign;

        if ($campaign) {
            return $this->item($campaign, new CampaignTransformer);
        }

        return null;
    }

    /**
     * Include Group
     *
     * @param Trip $trip
     * @return \League\Fractal\Resource\Item
     */
    public function includeGroup(Trip $trip)
    {
        $group = $trip->group;

        if ($group) {
            return $this->item($group, new GroupTransformer);
        }

        return null;
    }

    /**
     * Include Trip
     *
     * @param Trip $trip
     * @return \League\Fractal\Resource\Item
     */
    public function includeDeadlines(Trip $trip)
    {
        $deadlines = $trip->deadlines;

        if ($deadlines) {
            return $this->collection($deadlines, new DeadlineTransformer);
        }

        return null;
    }

    /**
     * Include Requirements
     *
     * @param Trip $trip
     * @return \League\Fractal\Resource\Collection
     */
    public function includeRequireables(Trip $trip)
    {
        $requirements = $trip->requireables;

        return $this->collection($requirements, new RequirementTransformer);
    }

    /**
     * Include Facilitators
     *
     * @param Trip $trip
     * @return \League\Fractal\Resource\Collection
     */
    public function includeFacilitators(Trip $trip)
    {
        $facilitators = $trip->facilitators;

        return $this->collection($facilitators, new UserTransformer);
    }

    /**
     * Include Rep
     *
     * @param Trip $trip
     * @return \League\Fractal\Resource\Collection
     */
    public function includeRep(Trip $trip)
    {
        $rep = $trip->rep;

        if (! $rep) {
            return null;
        }

        return $this->item($rep, new UserTransformer);
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
