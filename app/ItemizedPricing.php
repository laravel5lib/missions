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
       $tripRegistrationPrices = $this->getAllTripRegistrationCosts();

       $lastIncrement = $tripRegistrationPrices->shift();

       $registrationPrices = $tripRegistrationPrices->reject(function($price) {
           return $price->active_at < $this->reservation->getCurrentRate()->active_at;
       });

       $transformedAmounts = $registrationPrices->map(function($price) use($lastIncrement) {
           
            $payment = $price->payments()->whereDate('due_at', '>=', now())->first();

            return [
                'name' => $price->cost->name.' Discount',
                'amount' => '($'. number_format($lastIncrement->amount - $price->amount, 2, '.', '') .')',
                'amount_due' => '$'. number_format($this->reservation->getTotalCost()/100 * ($payment->percentage_due/100), 2, '.', ''),
                'due_at' => $payment->due_at->format('M d, Y')
            ];
       })->all();

       $otherPrices = $this->getNonRegistrationCosts();
       
       $costs = array_merge( 
           $otherPrices->map(function($price) {
               return [
                   'name' => $price->cost->name,
                   'amount' => '$'.number_format($price->amount, 2, '.', '')
               ];
           })->all(),
           [$lastIncrement ? [
               'name' => $lastIncrement->cost->name ?? 'N/A',
               'amount' => '$'.number_format($lastIncrement->amount ?? 0, 2, '.', '')
           ] : [] ]
        );

        return array_merge($costs, $transformedAmounts);
    }

    private function getAllTripRegistrationCosts()
    {
        return $this->reservation->trip
            ->priceables()
            ->whereHas('cost', function($query) {
                $query->whereType('incremental');
            })
            ->orderBy('active_at', 'desc')
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
}