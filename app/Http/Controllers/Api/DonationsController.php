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

    public function store(DonationRequest $request)
    {
        if($request->has('card')) {
            $this->chargeCard($request->only(
                'card', 'stripe_id', 'amount', 'currency'
            ));
        }

        $donation = $this->donation->create($request->all());

        return $this->response->item($donation, new DonationTransformer);
    }

    protected function chargeCard($params, $capture = true)
    {
        $charge = $this->stripe->charges()->create([
            'customer' => isset($params['stripe_id']) ? $params['stripe_id'] : null,
            'currency' => $params['currency'],
            'amount'   => $params['amount'],
            'source'   => [
                'number'    => '4242424242424242', // $params['card']['number']
                'exp_month' => 10,
                'cvc'       => 314,
                'exp_year'  => 2020,
            ],
            'capture'  => $capture
        ]);

        return $charge;
    }
}
