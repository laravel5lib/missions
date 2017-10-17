<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\v1\DonorRequest;
use App\Http\Requests\v1\ExportRequest;
use App\Http\Transformers\v1\DonorTransformer;
use App\Jobs\ExportDonors;
use App\Models\v1\Donor;
use App\Services\PaymentGateway;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DonorsController extends Controller
{
    /**
     * @var Donor
     */
    private $donor;
    /**
     * @var PaymentGateway
     */
    private $merchant;

    /**
     * DonorsController constructor.
     * @param Donor $donor
     * @param PaymentGateway $merchant
     */
    public function __construct(Donor $donor, PaymentGateway $merchant)
    {
        $this->donor = $donor;
        $this->merchant = $merchant;
    }

    /**
     * Get a list of donors.
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request)
    {
        $donors = $this->donor
            ->filter($request->all())
            ->paginate($request->get('per_page', 10));

        return $this->response->paginator($donors, new DonorTransformer);
    }

    /**
     * Get the specified donor.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($id)
    {
        $donor = $this->donor->findOrFail($id);

        return $this->response->item($donor, new DonorTransformer);
    }

    /**
     * Create a new donor.
     *
     * @param DonorRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(DonorRequest $request)
    {
        if (! $request->get('customer_id')) {
            // create customer with donor details
            $customer = $this->merchant->createCustomer($request->all());

            // set the new customer id
            $request['customer_id'] = $customer['id'];
        }

        $donor = $this->donor->create($request->all());

        if ($request->has('tags')) {
            $donor->tag($request->get('tags'));
        }

        return $this->response->item($donor, new DonorTransformer);
    }

    /**
     * Update the specified donor.
     *
     * @param DonorRequest $request
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function update(DonorRequest $request, $id)
    {
        $donor = $this->donor->findOrFail($id);

        $donor->update($request->all());

        if ($request->has('tags')) {
            $donor->retag($request->get('tags'));
        }

        return $this->response->item($donor, new DonorTransformer);
    }

    /**
     * Delete the specified donor and all it's donations.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($id)
    {
        $donor = $this->donor->with('donations')->findOrFail($id);

        DB::transaction(function () use ($donor) {
            $donor->donations()->delete();
            $donor->delete();
        });

        return $this->response->noContent();
    }

    /**
     * Export donors.
     *
     * @param ExportRequest $request
     * @return mixed
     */
    public function export(ExportRequest $request)
    {
        $this->dispatch(new ExportDonors($request->all()));

        return $this->response()->created(null, [
            'message' => 'Report is being generated and will be available shortly.'
        ]);
    }
}
