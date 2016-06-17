<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\v1\Fundraiser;
use App\Http\Requests\v1\FundraiserRequest;
use App\Http\Transformers\v1\FundraiserTransformer;

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

        $this->middleware('api.auth', ['except' => ['index','show']]);
        $this->middleware('jwt.refresh', ['except' => ['index','show']]);
    }

    /**
     * Get all fundraisers.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function index()
    {
        $fundraisers = $this->fundraiser->paginate(25);

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
