<?php

namespace App\Http\Controllers\Api;

use App\Models\v1\Trip;
use App\Models\v1\Donor;
use Illuminate\Http\Request;
use App\Services\PaymentGateway;
use App\Events\RegisteredForTrip;
use App\Http\Requests\v1\TripRegistrationRequest;
use App\Http\Transformers\v1\ReservationTransformer;
use App\Http\Controllers\Controller;

class TripRegistrationController extends Controller
{
    protected $gateway;
    protected $trip;

    public function __construct(Trip $trip, PaymentGateway $gateway)
    {
        $this->trip = $trip;
        $this->gateway = $gateway;
    }

    /**
     * Create a Reservation
     *
     * @param String $id
     * @param TripRegistrationRequest $request
     * @return Response
     */
    public function store($id, TripRegistrationRequest $request)
    {
        if ($request->get('amount') > 0) {
            $request = $this->handleDeposit($request);
        }

        $reservation = $this->createReservation(
            $request,
            $this->trip->findOrFail($id)
        );

        event(new RegisteredForTrip($reservation, $request));

        return $this->response->item($reservation, new ReservationTransformer);
    }

    /**
     * Handle Deposit
     *
     * @param Request $request
     * @return Request
     */
    private function handleDeposit($request)
    {
        $customer = $this->createCustomer($request);

        // merge the customer id with donor details
        $request['donor'] = $request['donor'] + ['customer_id' => $customer['id']];

        $charge = $this->chargeCustomerCard($request, $customer);

        $request['details'] = [
            'type' => 'card',
            'charge_id' => $charge['id'],
            'brand' => $charge['source']['brand'],
            'last_four' => $charge['source']['last4'],
            'cardholder' => $charge['source']['name'],
        ];

        return $request;
    }

    /**
     * Create Customer
     *
     * @param Request $request
     * @return Array
     */
    private function createCustomer($request)
    {
        return $this->gateway->createCustomer($request->get('donor'), $request->get('token'));
    }
    
    /**
     * Charge a Customer's Credit Card
     *
     * @param Request $request
     * @param Array $customer
     * @return Array
     */
    private function chargeCustomerCard($request, $customer)
    {
        $charge = $this->createCharge($request, $customer['default_source'], $customer['id']);

        $this->gateway->captureCharge($charge['id']);

        return $charge;
    }

    /**
     * Create the Charge Array
     *
     * @param Request $request
     * @param String $cardToken
     * @param String $customerId
     * @return Array
     */
    private function createCharge($request, $cardToken, $customerId)
    {
        return $this->gateway->createCharge(
            $request->only(
                'donor',
                'payment',
                'token',
                'amount',
                'donor_id',
                'currency',
                'description'
            ),
            $cardToken,
            $customerId
        );
    }

    /**
     * Create Reservation
     *
     * @param Request $request
     * @param Trip $trip
     * @return Reservation
     */
    private function createReservation($request, $trip)
    {
        $data = [
            'given_names' => trim($request->get('given_names')),
            'surname' => trim($request->get('surname')),
            'gender' => $request->get('gender'),
            'status' => $request->get('status'),
            'shirt_size' => $request->get('shirt_size'),
            'birthday' => $request->get('birthday'),
            'phone_one' => stripPhone($request->get('phone_one')),
            'phone_two' => stripPhone($request->get('phone_two')),
            'address' => trim(ucwords(strtolower($request->get('address')))),
            'city' => trim(ucwords(strtolower($request->get('city')))),
            'state' => trim(ucwords(strtolower($request->get('state')))),
            'zip' => trim(strtoupper($request->get('zip'))),
            'country_code' => $request->get('country_code'),
            'user_id' => $request->get('user_id'),
            'email' => trim(strtolower($request->get('email'))),
            'desired_role' => $request->get('desired_role'),
            'shirt_size' => $request->get('shirt_size'),
            'avatar_upload_id' => $request->get('avatar_upload_id'),
            'companion_limit' => $trip->companion_limit
        ];

        return $trip->reservations()->create($data);
    }
}
