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

        $prices = $otherPrices->merge($registrationRates);

        // transform incremental costs into discounts of each other except for the last one
        $previousAmount = null;
        return $prices->map(function($price) use($previousAmount) {
            $array = [
                'name' => $price->cost->name,
                'description' => $price->cost->description,
                'amount' => currency($price->amount)
            ];

            if ($price->cost->type === 'incremental') {
                $payment = $price->payments()
                    ->whereDate('due_at', '>=', now())
                    ->first();

                $array['amount_due'] = $previousAmount ? $previousAmount - $price->amount : $price->amount;
                $array['due_at'] = $payment->due_at->format('M d, Y h:i a');

                $previousAmount = $price->amount;
            }

            return $array;

        })->all();
    }

    private function getTripRates($currentRate)
    {
        return $this->reservation->trip
            ->priceables()
            ->whereHas('cost', function($q) {
                return $q->where('type', 'incremental');
            })
            ->whereDate('active_at', '>', $currentRate->active_at->toDateString())
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

    public function lateFee()
    {
        if ($this->reservation->getPercentRaised() >= 100) return null;

        $reservationLateFeePriceIds = $this->reservation
            ->priceables()
            ->whereHas('cost', function($query) {
                $query->where('type', 'fee');
            })
            ->pluck('id')
            ->toArray();

        $lateFee = $this->reservation
            ->trip
            ->priceables()
            ->whereHas('cost', function($query) {
                $query->where('type', 'fee');
            })
            ->whereNotIn('id', $reservationLateFeePriceIds)
            ->whereDate('active_at', '>=', now())
            ->first();

        if (! $lateFee) return null;
        
        return [
            'late_fee' => '$'.number_format($lateFee->amount ?? 0, 2, '.', ''),
            'applied_at' => $lateFee ? $lateFee->active_at->format('M d, Y') : ''
        ];
    }
}