<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\AccommodationRequest;
use App\Http\Transformers\v1\AccommodationTransformer;
use App\Models\v1\Accommodation;
use Illuminate\Http\Request;

use App\Http\Requests;

class AccommodationsController extends Controller
{

    /**
     * @var Accommodation
     */
    private $accommodation;

    /**
     * AccommodationsController constructor.
     * @param Accommodation $accommodation
     */
    public function __construct(Accommodation $accommodation)
    {
        $this->accommodation = $accommodation;
        $this->middleware('api.auth');
        $this->middleware('jwt.refresh');
    }

    /**
     * Get a list of accommodations
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request)
    {
        $accommodation = $this->accommodation
                              ->filter($request->all())
                              ->paginate($request->get('per_page', 10));

        return $this->response->paginator($accommodation, new AccommodationTransformer);
    }

    /**
     * Create a new accommodation
     *
     * @param AccommodationRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(AccommodationRequest $request)
    {
        $accommodation = $this->accommodation->create($request->all());

        return $this->response->item($accommodation, new AccommodationTransformer);
    }

    /**
     * Get the specified accommodation
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($id)
    {
        $accommodation = $this->accommodation->findOrFail($id);

        return $this->response->item($accommodation, new AccommodationTransformer);
    }

    /**
     * Update the specified accommodation
     *
     * @param $id
     * @param AccommodationRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function update($id, AccommodationRequest $request)
    {
        $accommodation = $this->accommodation->findOrFail($id);

        $accommodation->update($request->all());

        return $this->response->item($accommodation, new AccommodationTransformer);
    }

    /**
     * Delete the specified accommodation
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($id)
    {
        $accommodation = $this->accommodation->findOrFail($id);

        $accommodation->delete();

        return $this->response->noContent();
    }
}
