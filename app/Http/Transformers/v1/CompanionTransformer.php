<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\Companion;
use League\Fractal\TransformerAbstract;

class CompanionTransformer extends TransformerAbstract
{
    /**
     * List of resources available to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'reservation', 'companion_reservation'
    ];

    /**
     * Transform the object into a basic array
     *
     * @param Companion $companion
     * @return array
     */
    public function transform(Companion $companion)
    {
        $companion->load('companion_reservation');

        $array = [
            'name'           => $companion->companion_reservation->name,
            'age'            => $companion->companion_reservation->age,
            'gender'         => $companion->companion_reservation->gender,
            'status'         => $companion->companion_reservation->status,
            'relationship'   => $companion->relationship,
            'reservation_id' => $companion->companion_reservation_id,
            'links'          => [
                [
                    'rel' => 'reservation',
                    'uri' => '/reservations/' . $companion->companion_reservation_id,
                ]
            ]
        ];

        return $array;
    }

    /**
     * Include Reservation
     *
     * @param Companion $companion
     * @return \League\Fractal\Resource\Item
     */
    public function includeReservation(Companion $companion)
    {
        $reservation = $companion->reservation;

        return $this->item($reservation, new ReservationTransformer);
    }

    /**
     * Include Companion Reservation
     *
     * @param Companion $companion
     * @return \League\Fractal\Resource\Item
     */
    public function includeCompanionReservation(Companion $companion)
    {
        $reservation = $companion->companion_reservation;

        return $this->item($reservation, new ReservationTransformer);
    }
}