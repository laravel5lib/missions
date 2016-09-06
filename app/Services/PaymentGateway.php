<?php

namespace App\Services;

use Cartalyst\Stripe\Stripe;

class PaymentGateway {

    /**
     * @var Stripe
     */
    private $stripe;

    /**
     * PaymentGateway constructor.
     * @param Stripe $stripe
     */
    public function __construct(Stripe $stripe)
    {
        $this->stripe = $stripe;
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
        $charge_id = $this->createCharge($params, $token);

        // Capture the charge
        $response = $this->captureCharge($charge_id);

        return $response;
    }


    /**
     * Create a Stripe Card Token.
     *
     * @param array $params
     * @return mixed
     */
    public function createCardToken($params)
    {
        // was a card id provided?
        if (is_string($params)) {
            $card = $params;
        }

        // was an array of card details provided?
        if (is_array($params)) {

            $card = [
                'card' => [
                    'name'      => $params['cardholder'],
                    'number'    => $params['number'],
                    'exp_month' => $params['exp_month'],
                    'exp_year'  => $params['exp_year'],
                    'cvc'       => $params['cvc'],
                ],
            ];
        }

        $token = $this->stripe->tokens()->create($card);

        return $token['id'];
    }

    /**
     * Create a Stripe Customer with default card.
     *
     * @param array $params
     * @param $card_token
     * @return mixed
     */
    public function createCustomer(array $params, $card_token)
    {
        $customer = $this->stripe->customers()->create([
            'email' => $params['email'],
            'source' => $card_token
        ]);

        return $customer;
    }

    /**
     * Create a new card and add it to customer.
     *
     * @param $customer_id
     * @param $card_token
     * @return mixed
     */
    public function createCard($customer_id, $card_token)
    {
        $card = $this->stripe->cards()->create([
            $customer_id,
            $card_token
        ]);

        return $card;
    }

    /**
     * Create a charge.
     *
     * @param array $params
     * @param null $customer_id
     * @param null $card_id
     * @return mixed
     */
    public function createCharge(array $params, $card_id, $customer_id = null)
    {
        $charge = $this->stripe->charges()->create([
            'customer'    => $customer_id,
            'currency'    => $params['currency'],
            'amount'      => $params['amount'],
            'source'      => $card_id,
            'description' => $params['description'],
            'capture'     => false
        ]);

        return $charge;
    }

    /**
     * Capture a charge.
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
     * Refund a charge.
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