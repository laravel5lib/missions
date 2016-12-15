<?php

namespace App\Http\Controllers\Api;

use App\Http\Transformers\v1\RequirementTransformer;
use App\Http\Transformers\v1\ReservationRequirementTransformer;
use App\Models\v1\Reservation;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ReservationRequirementsController extends Controller
{

    /**
     * @var Reservation
     */
    private $reservation;

    /**
     * ReservationRequirementsController constructor.
     * @param Reservation $reservation
     */
    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    /**
     * Get all Reservation Requirements.
     *
     * @param Request $request
     * @param $reservationId
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request, $reservationId)
    {
        $requirements = $this->reservation
                            ->findOrFail($reservationId)
                            ->requirements()
                            ->paginate($request->get('per_page', 10));

        return $this->response->paginator($requirements, new ReservationRequirementTransformer);
    }
}
