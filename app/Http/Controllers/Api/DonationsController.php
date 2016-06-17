<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\v1\Donation;
use App\Http\Transformers\v1\DonationTransformer;

class DonationsController extends Controller
{

    /**
     * @var Donation
     */
    private $donation;

    /**
     * DonationsController constructor.
     *
     * @param Donation $donation
     */
    public function __construct(Donation $donation)
    {
        $this->donation = $donation;

        $this->middleware('api.auth');
        $this->middleware('jwt.refresh');
    }

    /**
     * Get all donations.
     *
     * @param null $fundraiser_id
     * @return \Dingo\Api\Http\Response
     */
    public function index($fundraiser_id = null)
    {
        $donations = $this->donation;
        if ($fundraiser_id) $donations = $donations->whereFundraiser($fundraiser_id);
        $donations = $donations->paginate(25);

        return $this->response->paginator($donations, new DonationTransformer);
    }

    /**
     * Get one donation.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($id)
    {
        $donation = $this->donation->findOrFail($id);

        return $this->response->item($donation, new DonationTransformer);
    }
}
