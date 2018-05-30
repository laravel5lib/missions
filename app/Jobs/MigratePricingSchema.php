<?php

namespace App\Jobs;

use App\Models\v1\Trip;
use App\Models\v1\Campaign;
use Illuminate\Bus\Queueable;
use App\Models\v1\CampaignGroup;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class MigratePricingSchema implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $trip;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Trip $trip)
    {
        $this->trip = $trip;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $costs = DB::table('costs')
            ->where('cost_assignable_id', $this->trip->id)
            ->where('cost_assignable_type', 'trips')
            ->get();

        // create or find campaign costs
        $campaign = Campaign::where('id', $this->trip->campaign_id)->withTrashed()->firstOrFail();
        foreach ($costs as $cost) 
        {
            $campaign->costs()->firstOrCreate([
                'name' => $cost->name,
                'type' => $this->getCostType($cost)
            ], [
                'description' => $cost->description
            ]);
        }

        // add group to campaign
        $group = CampaignGroup::firstOrCreate([
            'campaign_id' => $this->trip->campaign_id,
            'group_id' => $this->trip->group_id,
            'status_id' => 1
        ]);

        // add pricing to group and trip
        foreach ($costs as $cost) 
        {
            $campaignCost = $campaign->costs()->where('name', $cost->name)->first();

            $price = [
                'cost_id' => $campaignCost->id,
                'amount' => $cost->amount/100,
                'active_at' => $cost->active_at,
            ];

            if ($cost->type === 'incremental') {
                $payments = DB::table('payments')->where('cost_id', $cost->id)->orderBy('due_at')->get();
                
                $price['payments'] = $payments->map(function($payment, $index) use($payments) {
                    return [
                        'percentage_due' => count($payments) === $index+1 ? 100 : $payment->percent_owed,
                        'due_at' => $payment->due_at,
                        'grace_days' => $payment->grace_period
                    ];
                })->all();
            }

            $campaignGroup = CampaignGroup::where('group_id', $this->trip->group_id)
                ->where('campaign_id', $this->trip->campaign_id)
                ->firstOrFail();

            $groupPrice = $campaignGroup->prices()->where('cost_id', $price['cost_id'])->first();

            // if price doesn't already exist, add it to the group
            if ( ! $groupPrice) {
                $groupPrice = $campaignGroup->prices()->create([
                    'cost_id' => $price['cost_id'],
                    'amount' => $price['amount'],
                    'active_at' => $price['active_at']
                ]);

                if (isset($price['payments']) && $price['payments']) {
                    $groupPrice->syncPayments($price['payments']);
                }
            }

            // if price amount doesn't match the group's amount, then add it as a custom price
            $groupPrice->amount === $price['amount'] 
                ? $this->trip->attachPriceToModel($groupPrice->id) 
                : $this->trip->addPrice($price);
        }

        // add pricing to reservations
        foreach ($this->trip->reservations as $reservation) 
        {
            $costs = DB::table('reservation_costs')
                ->join('costs', 'costs.id', '=', 'reservation_costs.cost_id')
                ->where('reservation_costs.reservation_id', $reservation->id)
                ->get();

            foreach($costs as $cost) 
            {
                $price = $this->trip->priceables()->whereHas('cost', function ($q) use ($cost) {
                    return $q->where('name', $cost->name);
                })->first();

                if (!$price) break;

                $reservation->attachPriceToModel($price->id);

                if ($cost->locked) {
                    $reservation->lockPrice($price);
                }
            }
        }
    }

    private function getCostType($cost)
    {
        if ($cost->name === 'Deposit') {
            return 'upfront';
        }

        if ($cost->name === 'Late Registration') {
            return 'fee';
        }
        
        return $cost->type;
    }
}
