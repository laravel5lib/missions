<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Transformers\v1\DonorTransformer;
use App\Models\v1\Donor;
use App\Models\v1\Fundraiser;
use App\Http\Requests\v1\FundraiserRequest;
use App\Http\Transformers\v1\FundraiserTransformer;
use Dingo\Api\Contract\Http\Request;

class FundraisersController extends Controller
{
    protected $fundraiser;

    /**
     * Instantiate a new UsersController instance.
     *
     * @param Fundraiser $fundraiser
     */
    public function __construct(Fundraiser $fundraiser)
    {
        $this->fundraiser = $fundraiser;

        $this->middleware('api.auth', ['except' => ['index','show', 'donors']]);
    }

    /**
     * Get all fundraisers.
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request)
    {
        $fundraisers = $this->fundraiser->filter($request->all())->paginate(25);

        return $this->response->paginator($fundraisers, new FundraiserTransformer);
    }

    /**
     * Get one fundraiser.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($id)
    {
        $fundraiser = $this->fundraiser->findOrFail($id);

        return $this->response->item($fundraiser, new FundraiserTransformer);
    }

    /**
     * Get a list of donors.
     *
     * @param $id
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function donors($id, Request $request)
    {
        $fundraiser = $this->fundraiser->findOrFail($id);

        // Filter donors by the fundraiser's designation
        $designation = [str_singular($fundraiser->fundable_type) => $fundraiser->fundable_id];
        $request->merge($designation);

        // Filter by fundraiser's start and end dates.
        if( ! $request->has('starts'))
            $request->merge(['starts' => $fundraiser->started_at]);

        if( ! $request->has('ends'))
            $request->merge(['ends' => $fundraiser->ended_at]);

        $donors = Donor::filter($request->all())
            ->paginate($request->get('per_page'));

        // Pass the designation to the transformer to filter
        // embedded relationships by designation.
        return $this->response->paginator($donors, new DonorTransformer($designation));
    }

    /**
     * Create a new fundraiser and save in storage.
     *
     * @param FundraiserRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(FundraiserRequest $request)
    {
        $fundraiser = new Fundraiser($request->all());

        $fundraiser->save();

        $location = url('/fundraisers/' . $fundraiser->id);

        return $this->response->created($location);
    }

    /**
     * Update a specific fundraiser in storage.
     *
     * @param FundraiserRequest $request
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function update(FundraiserRequest $request, $id)
    {
        $fundraiser = $this->fundraiser->findOrFail($id);

        $fundraiser->update($request->all());

        return $this->response->item($fundraiser, new FundraiserTransformer);
    }

    /**
     * Delete a specific fundraiser from storage.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($id)
    {
        $fundraiser = $this->fundraiser->findOrFail($id);

        $fundraiser->delete();

        return $this->response->noContent();
    }
}
