<?php

namespace App\Http\Controllers\Api;

use App\Events\DonationWasMade;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\CardRequest;
use App\Http\Requests\v1\DonationRequest;
use App\Http\Transformers\v1\DonationTransformer;
use App\Models\v1\Transaction;
use App\Http\Transformers\v1\TransactionTransformer;
use App\Models\v1\Donor;
use App\Services\PaymentGateway;
use Dingo\Api\Contract\Http\Request;

class DonationsController extends Controller
{

    /**
     * @var PaymentGateway
     */
    private $payment;

    /**
     * @var Donor
     */
    private $donor;
    /**
     * @var Transaction
     */
    private $transaction;

    /**
     * DonationsController constructor.
     *
     * @param Transaction $transaction
     * @param PaymentGateway $payment
     * @param Donor $donor
     */
    public function __construct(Transaction $transaction, PaymentGateway $payment, Donor $donor)
    {
        $this->transaction = $transaction;
        $this->payment = $payment;
        $this->donor = $donor;

//        $this->middleware('api.auth');
    }

    /**
     * Get all donations.
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request)
    {
        $donations = $this->transaction
            ->filter($request->all())
            ->paginate($request->get('per_page', 10));

        return $this->response->paginator($donations, new TransactionTransformer);
    }

    /**
     * Get one donation.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($id)
    {
        $donation = $this->transaction->findOrFail($id);

        return $this->response->item($donation, new TransactionTransformer);
    }

    /**
     * Create a new donation.
     *
     * @param DonationRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(DonationRequest $request)
    {
        // has the card been authorized and a charge object created already?
        // then only capture the charge
        if($request->has('charge_id')) {
            $this->payment->captureCharge($request->get('charge_id'));
        }

        // has no charge object been created? Then create and capture it.
        if($request->has('card')) {
            $this->payment->chargeAndCaptureCard($request->only(
                'card', 'customer_id', 'amount', 'currency', 'description'
            ));
        }

        // we can pass donor details to try and find a match
        // or to create a new donor if a match isn't found.
        if($request->has('donor')) {
            $donor = $this->donor->firstOrCreate($request->get('donor'));
        // Alternatively, we can use an existing donor by id.
        } else {
            $donor = $this->donor->findOrFail($request->get('donor_id'));
        }

        // Create the donation for the donor.
        $donation = $donor->donations()->create($request->all());

        event(new DonationWasMade($donation, $donor));

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
