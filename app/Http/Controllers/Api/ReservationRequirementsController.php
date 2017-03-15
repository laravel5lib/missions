<?php

namespace App\Http\Controllers\Api;

use App\Events\RequirementWasUpdated;
use App\Http\Transformers\v1\RequirementTransformer;
use App\Http\Transformers\v1\ReservationRequirementTransformer;
use App\Models\v1\Reservation;
use App\Models\v1\ReservationRequirement;
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
     * @var ReservationRequirement
     */
    private $requirement;

    /**
     * ReservationRequirementsController constructor.
     * @param Reservation $reservation
     * @param ReservationRequirement $requirement
     */
    public function __construct(Reservation $reservation, ReservationRequirement $requirement)
    {
        $this->reservation = $reservation;
        $this->requirement = $requirement;
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
                            ->withTrashed()
                            ->findOrFail($reservationId)
                            ->requirements()
                            ->filter($request->all())
                            ->paginate($request->get('per_page', 10));

        return $this->response->paginator($requirements, new ReservationRequirementTransformer);
    }

    /**
     * Get on reservation requirement.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($reservationId, $id)
    {
        $requirement = $this->requirement->findOrFail($id);

        return $this->response->item($requirement, new ReservationRequirementTransformer);
    }

    /**
     * Update a reservation requirement.
     *
     * @param $id
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function update(Request $request, $reservationId, $id)
    {
        $requirement = $this->requirement->findOrFail($id);

        $requirement->update([
            'status' => $request->get('status', $requirement->status),
            'grace_period' => $request->get('grace_period', $requirement->grace_period),
            'completed_at' => $request->get('completed_at', $requirement->completed_at),
            'document_id' => $request->get('document_id', $requirement->document_id)
        ]);

        event(new RequirementWasUpdated($requirement));

        return $this->response->item($requirement, new ReservationRequirementTransformer);
    }

    /**
     * Remove the requirement from the reservation.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($reservationId, $id)
    {
        $requirement = $this->requirement->findOrFail($id);

        $requirement->delete();

        return $this->response->noContent();
    }
}
