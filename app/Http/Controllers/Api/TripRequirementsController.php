<?php

namespace App\Http\Controllers\Api;

use App\Http\Transformers\v1\RequirementTransformer;
use App\Models\v1\Requirement;
use App\Models\v1\Trip;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class TripRequirementsController extends Controller
{

    /**
     * @var Trip
     */
    private $trip;
    /**
     * @var Requirement
     */
    private $requirement;

    /**
     * TripRequirementsController constructor.
     * @param Trip $trip
     * @param Requirement $requirement
     */
    public function __construct(Trip $trip, Requirement $requirement)
    {
        $this->trip = $trip;
        $this->requirement = $requirement;
    }

    /**
     * Get all trip requirements.
     *
     * @param Request $request
     * @param $tripId
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request, $tripId)
    {
        $requirements = $this->trip
                            ->findOrFail($tripId)
                            ->requirements()
                            ->paginate($request->get('per_page', 10));

        return $this->response->paginator($requirements, new RequirementTransformer);
    }

    /**
     * Get one trip requirement by id.
     *
     * @param $tripId
     * @param $requirementId
     * @return \Dingo\Api\Http\Response
     */
    public function show($tripId, $requirementId)
    {
        $requirement = $this->requirement
                            ->where('requester_id', $tripId)
                            ->findOrFail($requirementId);

        return $this->response->item($requirement, new RequirementTransformer);
    }
}
