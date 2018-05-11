<?php
namespace App;

use App\Models\v1\Reservation;

class ItemizedPricing
{
    protected $reservation;

    function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function breakdown()
    {  
        // get reservation current rate
        $currentRate = $this->reservation->getCurrentRate();

        // find any registration costs that come after the current rate
        $tripRates = $this->getTripRates($currentRate);

        // merge these costs
        $registrationRates = $tripRates->push($currentRate);

        // get other costs
        $otherPrices = $this->getNonRegistrationCosts();
        
        // merge all other costs
        $prices = $currentRate ? $otherPrices->merge($registrationRates) : $otherPrices;
        

        // transform incremental costs into discounts of each other except for the last one
        $previousAmount = null; // previous amount default
        $costs = []; // costs array default
        $i = 0; // iteration count default
        $len = $prices->count(); // get length of array
        foreach($prices as $index => $price) {
            $costs[$index] = [
                'name' => $price->cost->name,
                'description' => $price->cost->description,
                // if previous amount, get the difference
                'amount' => $previousAmount ? '('.currency($previousAmount - $price->amount).')' : currency($price->amount)
            ];

            if ($price->cost->type === 'incremental') {
                // grab the latest payment for this price
                $payment = $price->payments()
                    ->whereDate('due_at', '>=', now())
                    ->first();
                
                // if last iteration then add these keys
                if ($i == $len - 1) {
                    $costs[$index]['amount_due'] = currency(($this->reservation->getTotalCost()/100) * ($payment->percentage_due/100));
                    $costs[$index]['due_at'] = $payment->due_at->format('M d, Y h:i a');
                }

                // set the previous amount
                $previousAmount = $price->amount;
            }
            // increase iteration count
            $i++;
        };

        return $costs;
    }

    private function getTripRates($currentRate)
    {
        return $this->reservation->trip
            ->priceables()
            ->whereHas('cost', function($q) {
                return $q->where('type', 'incremental');
            })
            ->when($currentRate, function ($query) use ($currentRate) {
                return $query->whereDate('active_at', '>', $currentRate->active_at->toDateString());
            })
            ->get();
    }

    private function getNonRegistrationCosts()
    {
        return $this->reservation
            ->priceables()
            ->whereHas('cost', function($query) {
                $query->where('type', '<>', 'incremental');
            })
            ->get();
    }

    public function hasPendingLateFee()
    {
        // no late fee if fully funded
        if ($this->reservation->getPercentRaised() >= 100) return false;

        // get reservation current rate
        $currentRate = $this->reservation->getCurrentRate();

        if (! $currentRate) return false;

        // find any registration costs that come after the current rate
        $tripRates = $this->getTripRates($currentRate);

        // merge these costs
        $registrationRates = $tripRates->push($currentRate);

        $count = $registrationRates->filter(function($rate) {
            return $rate->cost->type === 'incremental';
        })->count();

        if ($count > 1) return false;

        return $this->lateFee();
    }

    public function lateFee()
    {
        $reservationLateFeePriceIds = $this->reservation
            ->priceables()
            ->whereHas('cost', function($query) {
                $query->where('type', 'fee');
            })
            ->pluck('id')
            ->toArray();

        $pendingLateFee = $this->reservation
            ->trip
            ->priceables()
            ->whereHas('cost', function($query) {
                $query->where('type', 'fee');
            })
            ->whereNotIn('id', $reservationLateFeePriceIds)
            ->whereDate('active_at', '>=', now())
            ->first();
        
        // if all late fees have already been applied, return null
        if (! $pendingLateFee) return null;
        
        return [
            'late_fee' => '$'.number_format($pendingLateFee->amount ?? 0, 2, '.', ''),
            'applied_at' => $pendingLateFee ? $pendingLateFee->active_at->format('M d, Y') : ''
        ];
    }
}