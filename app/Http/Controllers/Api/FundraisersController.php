<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Transformers\v1\DonationTransformer;
use App\Http\Transformers\v1\DonorTransformer;
use App\Http\Transformers\v1\TransactionTransformer;
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

//        $this->middleware('api.auth', ['only' => ['store','update','destroy']]);
    }

    /**
     * Get all fundraisers.
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request)
    {
        $fundraisers = $this->fundraiser->filter($request->all())->paginate($request->get('per_page', 25));

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

        $donors = $fundraiser->donors()
            ->filter($request->all())
            ->paginate($request->get('per_page', 10));

        // In order to calculate the total amount donated for the fundraiser
        // we have to pass in a fund id, a start date, and an end date.
        return $this->response->paginator($donors, new DonorTransformer([
            // These values will be used by the donation() method on the donor model.
            'fund_id' => $fundraiser->fund_id,
            'started_at' => $fundraiser->started_at,
            'ended_at' => $fundraiser->ended_at
        ]));
    }

    /**
     * Get a list of donations.
     *
     * @param $id
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function donations($id, Request $request)
    {
        $fundraiser = $this->fundraiser->findOrFail($id);

        $donations = $fundraiser->donations()
            ->filter($request->all())
            ->paginate($request->get('per_page', 10));

        return $this->response->paginator($donations, new DonationTransformer);
    }

    /**
     * Create a new fundraiser and save in storage.
     *
     * @param FundraiserRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(FundraiserRequest $request)
    {
        $fundraiser = $this->fundraiser->create($request->all());

        if ($request->has('tags')) {
            $fundraiser->tag($request->get('tags'));
        }

        if ($request->has('upload_ids')) {
            $fundraiser->uploads()->attach($request->get('upload_ids'));
        }

        return $this->response->item($fundraiser, new FundraiserTransformer);
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

        if ($request->has('tags')) {
            $fundraiser->retag($request->get('tags'));
        }

        if ($request->has('upload_ids')) {
            $fundraiser->uploads()->sync($request->get('upload_ids'));
        }

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
