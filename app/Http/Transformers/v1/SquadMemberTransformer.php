<?php

namespace App\Http\Transformers\v1;

use Carbon\Carbon;
use App\Models\v1\Reservation;
use League\Fractal\TransformerAbstract;

class SquadMemberTransformer extends TransformerAbstract
{
    /**
     * List of resources available to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'user', 'trip', 'notes', 'companions'
    ];

    /**
     * Turn this item object into a generic array
     *
     * @param Reservation $reservation
     * @return array
     */
    public function transform(Reservation $reservation)
    {
        $reservation->load('avatar');

        return [
            'id'                  => $reservation->id,
            'given_names'         => $reservation->given_names,
            'surname'             => $reservation->surname,
            'gender'              => $reservation->gender,
            'status'              => $reservation->status,
            'age'                 => $reservation->age,
            'avatar'              => $reservation->avatar ? image($reservation->avatar->source) : url('/images/placeholders/user-placeholder.png'),
            'desired_role'        => [ 
                                        'code' => $reservation->desired_role, 
                                        'name' => teamRole($reservation->desired_role) 
                                     ],
            'leader'              => $reservation->pivot ? (boolean) $reservation->pivot->leader : null,
            'created_at'          => $reservation->pivot->created_at->toDateTimeString(),
            'updated_at'          => $reservation->pivot->updated_at->toDateTimeString(),
            'links'               => [
                [
                    'rel' => 'self',
                    'uri' => '/api/reservations/' . $reservation->id,
                ]
            ]
        ];
    }

    /**
     * Include User
     *
     * @param Reservation $reservation
     * @return \League\Fractal\Resource\Item
     */
    public function includeUser(Reservation $reservation)
    {
        $user = $reservation->user;

        return $this->item($user, new UserTransformer);
    }

    /**
     * Include Trip
     *
     * @param Reservation $reservation
     * @return \League\Fractal\Resource\Item
     */
    public function includeTrip(Reservation $reservation)
    {
        $trip = $reservation->trip;

        return $this->item($trip, new TripTransformer);
    }

    /**
     * Include Notes
     *
     * @param Reservation $reservation
     * @return \League\Fractal\Resource\Collection
     */
    public function includeNotes(Reservation $reservation)
    {
        $notes = $reservation->notes;

        return $this->collection($notes, new NoteTransformer);
    }

    /**
     * Include Companions
     *
     * @param Reservation $reservation
     * @return \League\Fractal\Resource\Collection
     */
    public function includeCompanions(Reservation $reservation)
    {
        $companions = $reservation->companionReservations;

        return $this->collection($companions, new ReservationTransformer);
    }

}