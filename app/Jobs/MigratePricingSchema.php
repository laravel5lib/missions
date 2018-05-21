<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Models\v1\CampaignGroup;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class MigratePricingSchema implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $reservation;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $costs = $reservation->costs;

        // create or find campaign costs
        $campaign = $reservation->trip->campaign;
        foreach ($costs as $cost) 
        {
            $campaign->costs()->firstOrCreate([
                'name' => $cost->name
            ], [
                'type' => $cost->type,
                'description' => $cost->description
            ]);
        }

        // add group to campaign
        $group = CampaignGroup::create([
            'campaign_id' => $campaign->id,
            'group_id' => $reservation->trip->group_id,
            'status_id' => 1
        ]);

        // add pricing to trip
        $trip = $reservation->trip;
        foreach ($costs as $cost) 
        {
            $campaignCost = $campaign->costs()->where('name', $cost->name)->first();

            $price = [
                'cost_id' => $campaignCost->id,
                'amount' => $cost->amount,
                'active_at' => $cost->active_at,
            ];

            if ($cost->type === 'incremental') {
                $price['payments'] = $cost->payments->map(function($payment) {
                    return [
                        'percentage_due' => $payment->percent_owed, // last one needs to be 100%
                        'due_at' => $payment->due_at,
                        'grace_days' => $payment->grace_period
                    ];
                })->all();
            }

            $trip->addPrice($price);
        }

        // add pricing to reservation
        foreach ($costs as $cost) 
        {
            $price = $trip->priceables()->whereHas('cost', function ($q) use ($cost) {
                return $q->where('name', $cost->name);
            })->first();

            $reservation->attachPriceToModel($price->id);

            if ($cost->pivot->locked) {
                $reservation->lockPrice($price);
            }
        }
    }
}
