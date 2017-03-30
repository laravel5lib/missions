<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\v1\Reservation;
use App\Http\Controllers\Controller;
use App\Http\Transformers\v1\ReservationTransformer;

class ReservationTransfersController extends Controller
{
    private $reservation;
    
    function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }
    /**
     * Create a new transfer
     * 
     * @return Response
     */
    public function store($id, Request $request)
    {
        $reservation = $this->reservation->findOrFail($id);

        $transfer = $reservation->transferToTrip(
            $request->get('trip_id'),
            $request->get('desired_role')
        );

        return $this->response->item($transfer, new ReservationTransformer);
    }
}
