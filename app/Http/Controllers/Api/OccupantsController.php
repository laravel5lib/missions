<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\OccupantRequest;
use App\Http\Transformers\v1\OccupantTransformer;
use App\Models\v1\Occupant;
use Illuminate\Http\Request;

use App\Http\Requests;

class OccupantsController extends Controller
{

    /**
     * @var Occupant
     */
    private $occupant;

    /**
     * OccupantsController constructor.
     * @param Occupant $occupant
     */
    public function __construct(Occupant $occupant)
    {
        $this->middleware('api.auth');
        $this->middleware('jwt.refresh');
        $this->occupant = $occupant;
    }

    /**
     * Get a list of occupants
     *
     * @param $accommodation_id
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index($accommodation_id, Request $request)
    {
        $occupants = $this->occupant->where('accommodation_id', $accommodation_id)
                        ->filter($request->all())
                        ->paginate($request->get('per_page', 10));

        return $this->response->paginator($occupants, new OccupantTransformer);
    }

    /**
     * Create a new occupant
     *
     * @param $accommodation_id
     * @param OccupantRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store($accommodation_id, OccupantRequest $request)
    {
        $occupant = new Occupant($request->all());
        $occupant->accommodation_id = $accommodation_id;
        $occupant->save();

        return $this->response->item($occupant, new OccupantTransformer);
    }

    /**
     * Get the specified occupant
     *
     * @param $accommodation_id
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($accommodation_id, $id)
    {
        $occupant = $this->occupant
                         ->where('accommodation_id', $accommodation_id)
                         ->findOrFail($id);

        return $this->response->item($occupant, new OccupantTransformer);
    }

    /**
     * Update the specified occupant
     *
     * @param $accommodation_id
     * @param $id
     * @param OccupantRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function update($accommodation_id, $id, OccupantRequest $request)
    {
        $occupant = $this->occupant
                         ->where('accommodation_id', $accommodation_id)
                         ->findOrFail($id);

        $occupant->update($request->all());

        return $this->response->item($occupant, new OccupantTransformer);
    }

    /**
     * Delete the specified occupant
     *
     * @param $accommodation_id
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($accommodation_id, $id)
    {
        $occupant = $this->occupant
            ->where('accommodation_id', $accommodation_id)
            ->findOrFail($id);

        $occupant->delete();

        return $this->response->noContent();
    }
}
