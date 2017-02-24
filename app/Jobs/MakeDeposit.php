<?php

namespace App\Jobs;

use App\Events\TransactionWasCreated;
use App\Jobs\Job;
use App\Models\v1\Donor;
use App\Services\PaymentGateway;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MakeDeposit extends Job
{
    use SerializesModels;
    /**
     * @var array
     */
    private $params;

    /**
     * Create a new job instance.
     *
     * @param array $params
     */
    public function __construct(array $params)
    {
        $this->params = $params;
    }

    /**
     * Execute the job.
     *
     * @param PaymentGateway $gateway
     * @param Donor $donor
     */
    public function handle(PaymentGateway $gateway, Donor $donor)
    {
        // create customer with the token and donor details
        $customer = $gateway
            ->createCustomer($this->params['donor'], $this->params['token']);

        // merge the customer id with donor details
        $this->params['donor'] = $this->params['donor'] + ['customer_id' => $customer['id']];

        // create the charge with customer id, token, and donation details
        $charge = $gateway->createCharge(
            $this->params,
            $customer['default_source'],
            $customer['id']
        );

        // capture the charge
        $gateway->captureCharge($charge['id']);

        // rebuild the payment array with new details
        $request['payment'] = [
            'type' => 'card',
            'charge_id' => $charge['id'],
            'brand' => $charge['source']['brand'],
            'last_four' => $charge['source']['last4'],
            'cardholder' => $charge['source']['name'],
        ];

        // we can pass donor details to try and find a match
        // or to create a new donor if a match isn't found.
        if (isset($this->params['donor'])) {
            $donor = $donor->firstOrCreate($this->params['donor']);
            // Alternatively, we can use an existing donor by id.
        } else {
            $donor = $donor->findOrFail($this->params['donor_id']);
        }

        // Create the donation for the donor.
        $this->params = array_merge($this->params, [
            'type' => 'donation',
            'description' => 'Deposit toward ' . $this->params['fund_name']
        ]);
        $donation = $donor->donations()->create($this->params);

        event(new TransactionWasCreated($donation));
    }
}
