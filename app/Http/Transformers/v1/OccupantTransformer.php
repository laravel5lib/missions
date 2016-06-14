<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\Occupant;
use League\Fractal\TransformerAbstract;

class OccupantTransformer extends TransformerAbstract {

    /**
     * List of resources available to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'accommodation', 'reservation', 'roommates'
    ];

    /**
     * Transform the object into a basic array
     *
     * @param Occupant $occupant
     * @return array
     */
    public function transform(Occupant $occupant)
    {
        return [
            'id'               => $occupant->id,
            'room_no'          => $occupant->room_no,
            'accommodation_id' => $occupant->accommodation_id,
            'reservation_id'   => $occupant->reservation_id,
            'room_leader'      => (bool) $occupant->room_leader,
            'created_at'       => $occupant->created_at->toDateTimeString(),
            'updated_at'       => $occupant->updated_at->toDateTimeString(),
            'links'            => [
                [
                    'rel' => 'self',
                    'uri' => '/accommodations/' . $occupant->accommodation_id . '/occupants/' . $occupant->id
                ]
            ]
        ];
    }

    /**
     * Include Accommodation
     *
     * @param Occupant $occupant
     * @return \League\Fractal\Resource\Item
     */
    public function includeAccommodation(Occupant $occupant)
    {
        $accommodation = $occupant->accommodation;

        return $this->item($accommodation, new AccommodationTransformer);
    }

    /**
     * Include Reservation
     *
     * @param Occupant $occupant
     * @return \League\Fractal\Resource\Item
     */
    public function includeReservation(Occupant $occupant)
    {
        $reservation = $occupant->reservation;

        return $this->item($reservation, new ReservationTransformer);
    }

    /**
     * Include Roommates
     *
     * @param Occupant $occupant
     * @return \League\Fractal\Resource\Item
     */
    public function includeRoommates(Occupant $occupant)
    {
        $roommates = $occupant->roommates;

        return $this->collection($roommates, new OccupantTransformer);
    }
}