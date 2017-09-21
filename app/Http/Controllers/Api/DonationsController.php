<?php

namespace App\Http\Controllers\Api;

use App\Models\v1\Fund;
use App\Models\v1\Donor;
use App\Models\v1\Transaction;
use App\Events\DonationWasMade;
use App\Services\PaymentGateway;
use App\Http\Controllers\Controller;
use Dingo\Api\Contract\Http\Request;
use App\Events\TransactionWasCreated;
use App\Http\Requests\v1\CardRequest;
use App\Http\Requests\v1\DonationRequest;
use App\Http\Transformers\v1\DonationTransformer;
use App\Http\Transformers\v1\TransactionTransformer;

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
        // has a credit card token already been created and provided?
        // if not, tokenize the card details.
        if (! $request->has('token')) {
            $token = $this->payment->createCardToken($request->get('card'));
        } else {
            $token = $request->get('token');
        }

        // create customer with the token and donor details
        $customer = $this->payment
            ->createCustomer($request->get('donor'), $token);

        // merge the customer id with donor details
        $request['donor'] = $request->get('donor') + ['customer_id' => $customer['id']];

        // merge description with request
        $fund = Fund::find($request->get('fund_id'));
        $request['description'] = 'Donation' . $fund ? 'toward '.$fund->name : null;

        // add metadata
        $request->merge(['metadata' => [
            'donor_name' => $request->get('donor')['first_name'] . ' ' . $request->get('donor')['last_name'],
            'donor_email' => isset($request->get('donor')['email']) ? $request->get('donor')['email'] : null,
            'donor_phone' => isset($request->get('donor')['phone']) ? $request->get('donor')['phone'] : null,
            'fund' => $request->get('fund_id')
        ]]);

        // create the charge with customer id, token, and donation details
        $charge = $this->payment->createCharge(
            $request->all(),
            $customer['default_source'],
            $customer['id']
        );

        // capture the charge
        $this->payment->captureCharge($charge['id']);

        // rebuild the payment array with new details
        $request['details'] = [
            'type' => 'card',
            'charge_id' => $charge['id'],
            'brand' => $charge['source']['brand'],
            'last_four' => $charge['source']['last4'],
            'cardholder' => $charge['source']['name'],
        ];

        // we can pass donor details to try and find a match
        // or to create a new donor if a match isn't found.
        if ($request->has('donor')) {
            $donor = $this->donor->firstOrCreate($request->get('donor'));
        // Alternatively, we can use an existing donor by id.
        } else {
            $donor = $this->donor->findOrFail($request->get('donor_id'));
        }

        // Create the donation for the donor.
        $request->merge(['type' => 'donation']);
        $donation = $donor->donations()->create($request->all());

        event(new TransactionWasCreated($donation));

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
        return $this->payment->createCardToken($request->all());
    }
}
