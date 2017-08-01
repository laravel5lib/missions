<?php

namespace App\Http\Controllers\Api;

use App\Models\v1\Trip;
use App\Models\v1\Donor;
use App\Jobs\ExportTrips;
use App\Services\PaymentGateway;
use App\Events\RegisteredForTrip;
use App\Http\Controllers\Controller;
use Dingo\Api\Contract\Http\Request;
use App\Http\Requests\v1\TripRequest;
use App\Http\Requests\v1\ExportRequest;
use App\Http\Requests\v1\ImportRequest;
use App\Services\Importers\TripListImport;
use App\Http\Transformers\v1\TripTransformer;
use App\Http\Requests\v1\TripRegistrationRequest;
use App\Http\Transformers\v1\ReservationTransformer;

class TripsController extends Controller
{

    /**
     * @var Trip
     */
    private $trip;
    private $gateway;
    private $donor;

    /**
     * Instantiate a new TripsController instance.
     * @param Trip $trip
     */
    public function __construct(Trip $trip, PaymentGateway $gateway, Donor $donor)
    {
        $this->trip = $trip;
        $this->gateway = $gateway;
        $this->donor = $donor;
    }

    /**
     * Get a list of trips
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request)
    {
        $trips = $this->trip
                      ->withCount('reservations')
                      ->filter($request->all())
                      ->paginate($request->get('per_page', 10));

        return $this->response->paginator($trips, new TripTransformer);
    }

    /**
     * Get a single trip
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($id)
    {
        $trip = $this->trip->withCount('reservations')->findOrFail($id);

        return $this->response->item($trip, new TripTransformer);
    }

    /**
     * Create a new trip and save in storage
     *
     * @param TripRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(TripRequest $request)
    {
        $trip = $this->trip->create($request->all());

        if ($request->has('tags')) {
            $trip->tag($request->get('tags'));
        }

        return $this->response->item($trip, new TripTransformer);
    }

    /**
     * Duplicate a trip
     *
     * @param  TripRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function duplicate(TripRequest $request)
    {
        $trip = $this->trip->create($request->all());

        $trip->syncDeadlines($request->get('deadlines'));
        $trip->syncRequirements($request->get('requirements'));
        // $trip->syncCosts($request->get('costs'));

        if ($request->has('tags')) {
            $trip->tag($request->get('tags'));
        }

        return $this->response->item($trip, new TripTransformer);
    }

    /**
     * Update a trip in storage
     *
     * @param TripRequest $request
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function update(TripRequest $request, $id)
    {
        $trip = $this->trip->findOrFail($id);

        $trip->update($request->all());

        if ($request->has('tags')) {
            $trip->retag($request->get('tags'));
        }

        $trip->syncFacilitators($request->get('facilitators'));

        return $this->response->item($trip, new TripTransformer);
    }

    /**
     * Delete a trip
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($id)
    {
        $trip = Trip::findOrFail($id);
        
        $trip->delete();

        return $this->response->noContent();
    }

    public function checkPromoCode($id, Request $request)
    {
        $trip = $this->trip->findOrFail($id);

        $reward = $trip->applyCode($request->only('promocode'));

        return $reward ? (string) number_format($reward/100, 2) : abort(422, 'Invalid or expired promo code');
    }

    public function register($id, TripRegistrationRequest $request)
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

        $weight = $request->get('weight'); // kilograms
        $height = (int) $request->get('height_a').$request->get('height_b'); // centimeters

        if ($request->get('country_code') == 'us') {
            $weight = convert_to_kg($request->get('weight'));
        }
            $height = convert_to_cm($request->get('height_a'), $request->get('height_b'));

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
                'height' => $height,
                'weight' => $weight,
                'avatar_upload_id' => $request->get('avatar_upload_id'),
                'companion_limit' => $trip->companion_limit
            ]);

        event(new RegisteredForTrip($reservation, $request));

        return $this->response->item($reservation, new ReservationTransformer);
    }

    /**
     * Export trips.
     *
     * @param ExportRequest $request
     * @return mixed
     */
    public function export(ExportRequest $request)
    {
        $this->dispatch(new ExportTrips($request->all()));

        return $this->response()->created(null, [
            'message' => 'Report is being generated and will be available shortly.'
        ]);
    }

    /**
     * Import a list of trips.
     *
     * @param  TripListImport $import
     * @return response
     */
    public function import(ImportRequest $request, TripListImport $import)
    {
        $response = $import->handleImport();

        return $this->response()->created(null, $response);
    }
}
