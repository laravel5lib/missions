<?php

namespace App\Http\Controllers\Api;

use App\Models\v1\Trip;
use App\Models\v1\Donor;
use Illuminate\Http\Request;
use App\Services\PaymentGateway;
use App\Events\RegisteredForTrip;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\TripRegistrationRequest;
use App\Http\Transformers\v1\ReservationTransformer;

class TripRegistrationController extends Controller
{
    protected $gateway;
    protected $trip;

    public function __construct(Trip $trip, PaymentGateway $gateway)
    {
        $this->trip = $trip;
        $this->gateway = $gateway;
    }

    public function store($id, TripRegistrationRequest $request)
    {
        $trip = $this->trip->findOrFail($id);

        if ($request->get('amount') > 0) {
            // CAPTURE CARD
            // create customer with the token and donor details
            $customer = $this->gateway
                ->createCustomer($request->get('donor'), $request->get('token'));

            // merge the customer id with donor details
            // $requestdonor->merge(['customer_id' => $customer['id']]);
            $request['donor'] = $request['donor'] + ['customer_id' => $customer['id']];

            // create the charge with customer id, token, and donation details
            $charge = $this->gateway->createCharge(
                $request->only(
                    'donor',
                    'payment',
                    'token',
                    'amount',
                    'donor_id',
                    'currency',
                    'description'
                ),
                $customer['default_source'],
                $customer['id']
            );

            // capture the charge
            $this->gateway->captureCharge($charge['id']);

            $request['details'] = [
                'type' => 'card',
                'charge_id' => $charge['id'],
                'brand' => $charge['source']['brand'],
                'last_four' => $charge['source']['last4'],
                'cardholder' => $charge['source']['name'],
            ];
        }

        $reservation = $trip->reservations()
            ->create([
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
            ]);

        event(new RegisteredForTrip($reservation, $request));

        return $this->response->item($reservation, new ReservationTransformer);
    }
}
