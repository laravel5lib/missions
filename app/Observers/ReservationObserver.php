<?php

namespace App\Observers;

use App\Models\v1\Reservation;

class ReservationObserver
{
     /**
     * Listen to the Reservation created event.
     *
     * @param  Reservation  $reservation
     * @return void
     */
    public function created(Reservation $reservation)
    {
        $name = generateFundName($reservation);

        $reservation->fund()->create([
            'name' => $name,
            'slug' => generate_fund_slug($name),
            'balance' => 0,
            'class_id' => getAccountingClass($reservation)->id,
            'item_id'  => getAccountingItem($reservation)->id
        ]);
    }
}