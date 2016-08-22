<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\CardRequest;
use App\Http\Requests\v1\DonationRequest;
use App\Models\v1\Donation;
use App\Http\Transformers\v1\DonationTransformer;
use App\Models\v1\Donor;
use App\Services\PaymentGateway;
use Dingo\Api\Contract\Http\Request;

class DonationsController extends Controller
{

    /**
     * @var Donation
     */
    private $donation;

    /**
     * @var PaymentGateway
     */
    private $payment;

    /**
     * @var Donor
     */
    private $donor;

    /**
     * DonationsController constructor.
     *
     * @param Donation $donation
     * @param PaymentGateway $payment
     * @param Donor $donor
     */
    public function __construct(Donation $donation, PaymentGateway $payment, Donor $donor)
    {
        $this->donation = $donation;
        $this->payment = $payment;
        $this->donor = $donor;

        $this->middleware('api.auth');
    }

    /**
     * Get all donations.
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request)
    {
        $donations = $this->donation
            ->filter($request->all())
            ->paginate($request->get('per_page', 10));

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

    /**
     * Create a new donation.
     *
     * @param DonationRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(DonationRequest $request)
    {
        // has no charge object been created?
        if($request->has('card')) {
            $this->payment->chargeAndCaptureCard($request->only(
                'card', 'customer_id', 'amount', 'currency', 'description'
            ));
        }

        // has the card been authorized and a charge object created already?
        if($request->has('charge_id')) {
            $this->payment->captureCharge($request->get('charge_id'));
        }

        $donor = $this->donor->firstOrCreate($request->get('donor'));
        $donation = $donor->donations()->create($request->except('donor'));

        return $this->response->item($donation, new DonationTransformer);
    }

    /**
     * Only authorize the credit card and return the stripe charge object.
     *
     * @param CardRequest $request
     * @return mixed
     */
    public function authorizeCard(CardRequest $request)
    {
        $token = $this->payment->createCardToken($request->all());

        return $this->payment->createCharge(
            $request->all(),
            $token,
            $request->get('customer_id')
        );
    }
}
