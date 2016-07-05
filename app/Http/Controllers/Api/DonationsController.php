<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\v1\DonationRequest;
use App\Models\v1\Donation;
use App\Http\Transformers\v1\DonationTransformer;
use Cartalyst\Stripe\Stripe;

class DonationsController extends Controller
{

    /**
     * @var Donation
     */
    private $donation;
    /**
     * @var Stripe
     */
    private $stripe;

    /**
     * DonationsController constructor.
     *
     * @param Donation $donation
     * @param Stripe $stripe
     */
    public function __construct(Donation $donation, Stripe $stripe)
    {
        $this->donation = $donation;
        $this->stripe = $stripe;

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

    /**
     * Create a new donation.
     *
     * @param DonationRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(DonationRequest $request)
    {
        if($request->has('card')) {
            $this->chargeAndCaptureCard($request->only(
                'card', 'stripe_id', 'amount', 'currency', 'description'
            ));
        }

        $donation = $this->donation->create($request->all());

        return $this->response->item($donation, new DonationTransformer);
    }

    /**
     * Helper method that charges and
     * captures a credit card.
     *
     * @param $params
     * @return mixed
     */
    public function chargeAndCaptureCard($params)
    {
        // Tokenize card details
        $token = $this->createCardToken($params);

        // Create the charge object but don't capture
        $charge = $this->createCharge($params, $token);

        // Capture the charge
        $response =$this->captureCharge($charge);

        return $response;
    }


    /**
     * Create a Stripe Card Token.
     *
     * @param array $card
     * @return mixed
     */
    public function createCardToken(array $card)
    {
        $token = $this->stripe->tokens()->create([
            'card' => [
                'number'    => $card['number'],
                'exp_month' => $card['exp_month'],
                'exp_year'  => $card['exp_year'],
                'cvc'       => $card['cvc'],
            ],
        ]);

        return $token['id'];
    }

    /**
     * Create a Stripe Customer.
     *
     * @param array $customer
     * @param $card_token
     * @return mixed
     */
    public function createCustomer(array $customer, $card_token)
    {
        $customer = $this->stripe->customers()->create([
            'email' => $customer['email'],
            'source' => $card_token
        ]);

        return $customer['id'];
    }

    /**
     * Create a Stripe Charge.
     *
     * @param array $charge
     * @param null $customer_id
     * @param null $card_token
     * @return mixed
     */
    public function createCharge(array $charge, $card_token, $customer_id = null)
    {
        $charge = $this->stripe->charges()->create([
            'customer' => $customer_id,
            'currency' => $charge['currency'],
            'amount'   => $charge['amount'],
            'source'   => $card_token,
            'description' => $charge['description'],
            'statement_description' => $charge['description'],
            'capture'  => false
        ]);

        return $charge['id'];
    }

    /**
     * Capture a credit card charge.
     *
     * @param $charge_id
     * @param array $params
     * @return mixed
     */
    public function captureCharge($charge_id, $params = [])
    {
        return $this->stripe->charges()->capture($charge_id, $params);
    }

    /**
     * Refund a Charge.
     *
     * @param $charge_id
     * @param $amount
     * @return mixed
     */
    public function refundCharge($charge_id, $amount)
    {
        $refund = $this->stripe->refunds()->create($charge_id, $amount);

        return $refund[id];
    }
}
