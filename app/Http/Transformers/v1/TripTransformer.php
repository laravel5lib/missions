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
        'campaign', 'group', 'costs', 'deadlines', 'notes',
        'reservations', 'requirements', 'facilitators'
    ];

    /**
     * Turn this item object into a generic array
     *
     * @param Trip $trip
     * @return array
     */
    public function transform(Trip $trip)
    {
        $trip->load('reservations', 'costs');

        return [
            'id'              => $trip->id,
            'group_id'        => $trip->group_id,
            'campaign_id'     => $trip->campaign_id,
            'rep_id'          => $trip->rep_id,
            'spots'           => (int) $trip->spots,
            'status'          => $trip->status,
            'starting_cost'   => (int) $trip->starting_cost,
            'companion_limit' => (int) $trip->companion_limit,
            'reservations'    => (int) $trip->reservations()->count(),
            'country_code'    => $trip->country_code,
            'country_name'    => country($trip->country_code),
            'type'            => $trip->type,
            'difficulty'      => $trip->difficulty,
            'started_at'      => $trip->started_at->toDateString(),
            'ended_at'        => $trip->ended_at->toDateString(),
            'todos'           => $trip->todos,
            'prospects'       => $trip->prospects,
            'description'     => $trip->description,
            'published_at'    => $trip->published_at ? $trip->published_at->toDateTimeString() : null,
            'closed_at'       => $trip->closed_at->toDateTimeString(),
            'created_at'      => $trip->created_at->toDateTimeString(),
            'updated_at'      => $trip->updated_at->toDateTimeString(),
            'tags'            => $trip->tagNames(),
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

        if($campaign)
            return $this->item($campaign, new CampaignTransformer);

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

        if($group)
            return $this->item($group, new GroupTransformer);

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

        if($deadlines)
            return $this->collection($deadlines, new DeadlineTransformer);

        return null;
    }

    /**
     * Include Costs
     *
     * @param Trip $trip
     * @return \League\Fractal\Resource\Collection
     */
    public function includeCosts(Trip $trip, ParamBag $params = null)
    {

        // Optional params validation
        if ( ! is_null($params)) {
            $this->validateParams($params); 

            $costs = [];
            
            if (in_array('active', $params->get('status')))
            {
                $active = $trip->activeCosts;

                $maxDate = $active->where('type', 'incremental')->max('active_at');

                $costs = $active->reject(function ($value, $key) use($maxDate) {
                    return $value->type == 'incremental' && $value->active_at < $maxDate;
                });
            }
        
        } else {
            $costs = $trip->costs;
        }

        return $this->collection($costs, new CostTransformer);
    }

    /**
     * Include Notes
     *
     * @param Trip $trip
     * @return \League\Fractal\Resource\Collection
     */
    public function includeNotes(Trip $trip)
    {
        $notes = $trip->notes;

        return $this->collection($notes, new NoteTransformer);
    }

    /**
     * Include Reservations
     *
     * @param Trip $trip
     * @return \League\Fractal\Resource\Collection
     */
    public function includeReservations(Trip $trip)
    {
        $reservations = $trip->reservations;

        return $this->collection($reservations, new ReservationTransformer);
    }

    /**
     * Include Requirements
     *
     * @param Trip $trip
     * @return \League\Fractal\Resource\Collection
     */
    public function includeRequirements(Trip $trip)
    {
        $requirements = $trip->requirements;

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