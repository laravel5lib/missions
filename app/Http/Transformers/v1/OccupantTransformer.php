<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\Reservation;
use League\Fractal\TransformerAbstract;
use League\Fractal\ParamBag;

class OccupantTransformer extends TransformerAbstract
{

    private $validParams = ['status', 'type'];

    /**
     * List of resources available to include
     *
     * @var array
     */
    protected $availableIncludes = ['companions', 'squads', 'costs'];

    /**
     * Transform the object into a basic array
     *
     * @param Reservation $occupant
     * @return array
     */
    public function transform($occupant)
    {
        $data = [
            'id'               => $occupant->id,
            'avatar'           => $occupant->avatar ? image($occupant->avatar->source) : url('/images/placeholders/user-placeholder.png'),
            'given_names'      => $occupant->given_names,
            'surname'          => $occupant->surname,
            'age'              => $occupant->age,
            'gender'           => $occupant->gender,
            'desired_role'     => [
                'code' =>       $occupant->desired_role,
                'name' =>       teamRole($occupant->desired_role)
            ],
            'status'           => $occupant->status,
            'travel_group'        => $occupant->trip->group->name,
            'arrival_designation' => $occupant->designation ?
                implode('', array_flatten($occupant->designation->content)) : 'none',
            'room_leader'      => (bool) $occupant->pivot->room_leader,
            'created_at'       => $occupant->created_at->toDateTimeString(),
            'updated_at'       => $occupant->updated_at->toDateTimeString(),
            'links'            => [
                [
                    'rel' => 'self',
                    'uri' => '/rooms/' . $occupant->pivot->room_id . '/occupants/' . $occupant->id
                ]
            ]
        ];

        if ($occupant->pivot) {
            $data['relationship'] = $occupant->pivot->relationship;
        }

        return $data;
    }

    /**
     * Include Companions
     *
     * @param Reservation $occupant
     * @return \League\Fractal\Resource\Collection
     */
    public function includeCompanions(Reservation $occupant)
    {
        $companions = $occupant->companionReservations;

        return $this->collection($companions, new OccupantTransformer);
    }

    /**
     * Include Squads
     *
     * @param Reservation $occupant
     * @return \League\Fractal\Resource\Collection
     */
    public function includeSquads(Reservation $occupant)
    {
        $squads = $occupant->squads;

        return $this->collection($squads, new TeamSquadTransformer);
    }

    /**
     * Include Costs
     *
     * @param Reservation $reservation
     * @param ParamBag $params
     * @return \League\Fractal\Resource\Collection
     */
    public function includeCosts(Reservation $reservation, ParamBag $params = null)
    {
        // Optional params validation
        if (! is_null($params)) {
            $this->validateParams($params);

            $costs = [];

            if ($params->get('status') && in_array('active', $params->get('status'))) {
                $active = $reservation->activeCosts;

                $maxDate = $active->where('type', 'incremental')->max('active_at');

                $costs = $active->reject(function ($value, $key) use ($maxDate) {
                    return $value->type == 'incremental' && $value->active_at < $maxDate;
                });
            }

            if ($params->get('type')) {
                $costs = $reservation->costs()->where('type', $params->get('type'))->get();
            }
        } else {
            $costs = $reservation->costs;
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
