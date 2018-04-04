<?php
namespace App;

use App\Models\v1\Donor;
use Cartalyst\Stripe\Stripe;
use App\Services\PaymentGateway;
use App\Events\TransactionWasCreated;

class Deposit
{

    public static function create($fund, $request)
    {
        $gateway = new PaymentGateway(new Stripe(env('STRIPE_SECRET')));

        // CAPTURE CARD
        // create customer with the token and donor details
        $customer = $gateway->createCustomer($request->get('donor'), $request->get('token'));

        // merge the customer id with donor details
        // $requestdonor->merge(['customer_id' => $customer['id']]);
        $request['donor'] = $request['donor'] + ['customer_id' => $customer['id']];

        // create the charge with customer id, token, and donation details
        $charge = $gateway->createCharge(
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
        $gateway->captureCharge($charge['id']);

        $request['details'] = [
            'type' => 'card',
            'charge_id' => $charge['id'],
            'brand' => $charge['source']['brand'],
            'last_four' => $charge['source']['last4'],
            'cardholder' => $charge['source']['name'],
        ];

        // create transaction record
        $params = $request->only(
            'donor',
            'payment',
            'token',
            'amount',
            'donor_id',
            'currency',
            'description',
            'details'
        ) + ['fund_id' => $fund->id, 'fund_name' => $fund->name];

        if (isset($params['donor'])) {
            $donor = Donor::firstOrCreate($params['donor']);
            // Alternatively, we can use an existing donor by id.
        } else {
            $donor = Donor::findOrFail($params['donor_id']);
        }

        // Create the donation for the donor.
        $params = array_merge($params, [
            'type' => 'donation'
        ]);
        $donation = $donor->donations()->create($params);

        event(new TransactionWasCreated($donation));
    }
}