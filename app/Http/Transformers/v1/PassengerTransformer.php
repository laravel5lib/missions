<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\Passenger;
use League\Fractal\TransformerAbstract;

class PassengerTransformer extends TransformerAbstract
{

    protected $defaultIncludes = [];
    /**
     * List of resources available to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'transport', 'reservation'
    ];

    /**
     * Transform the object into a basic array
     *
     * @param Passenger $passenger
     * @return array
     */
    public function transform(Passenger $passenger)
    {

        return [
            'transportCompanions' => $passenger->transportCompanions(),
            'id'             => $passenger->id,
            'reservation_id' => $passenger->reservation_id,
            'transport_id'   => $passenger->transport_id,
            'seat_no'        => $passenger->seat_no,
            'created_at'     => $passenger->created_at->toDateTimeString(),
            'updated_at'     => $passenger->updated_at->toDateTimeString(),
            'links'          => [
                [
                    'rel' => 'self',
                    'uri' => '/transports/' . $passenger->transport_id . '/passengers/' . $passenger->id
                ]
            ]
        ];
    }

    /**
     * Include Transport
     *
     * @param Passenger $passenger
     * @return \League\Fractal\Resource\Item
     */
    public function includeTransport(Passenger $passenger)
    {
        $transport = $passenger->transport;

        return $this->item($transport, new TransportTransformer);
    }

    /**
     * Include Reservation
     *
     * @param Passenger $passenger
     * @return \League\Fractal\Resource\Item
     */
    public function includeReservation(Passenger $passenger)
    {
        $reservation = $passenger->reservation;

        return $this->item($reservation, new ReservationTransformer);
    }
}
