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
            'type' => 'donation'
        ]);
        $donation = $donor->donations()->create($this->params);

        event(new TransactionWasCreated($donation));
    }
}
