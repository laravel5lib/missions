<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\Facilitator;
use League\Fractal\TransformerAbstract;

class FacilitatorTransformer extends TransformerAbstract
{
    /**
     * List of resources available to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'trip', 'user', 'reservations'
    ];

    /**
     * Turn this item object into a generic array
     *
     * @param Facilitator $facilitator
     * @return array
     */
    public function transform(Facilitator $facilitator)
    {
        $facilitator->load('user');

        return [
            'user_id'         => $facilitator->user_id,
            'name'       => $facilitator->user->name,
            'email'      => $facilitator->user->email,
            'gender'     => $facilitator->user->gender,
            'created_at' => $facilitator->created_at->toDateTimeString(),
            'updated_at' => $facilitator->updated_at->toDateTimeString(),
            'links'      => [
                [
                    'rel' => 'self',
                    'uri' => '/facilitators/' . $facilitator->id,
                ]
            ],
        ];
    }

    /**
     * Include User
     *
     * @param Facilitator $facilitator
     * @return \League\Fractal\Resource\Item
     */
    public function includeUser(Facilitator $facilitator)
    {
        $user = $facilitator->user;

        return $this->item($user, new UserTransformer);
    }

    /**
     * Include Reservations
     *
     * @param Facilitator $facilitator
     * @return \League\Fractal\Resource\Item
     */
    public function includeReservations(Facilitator $facilitator)
    {
        $reservations = $facilitator->reservations;

        return $this->collection($reservations, new ReservationTransformer);
    }

    /**
     * Include Trip
     *
     * @param Facilitator $facilitator
     * @return \League\Fractal\Resource\Item
     */
    public function includeTrip(Facilitator $facilitator)
    {
        $trip = $facilitator->trip;

        return $this->item($trip, new TripTransformer);
    }
}