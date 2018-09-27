<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\v1\Reservation;
use App\Http\Controllers\Controller;

class ReservationPriceController extends Controller
{
    public function show($id, $price)
    {
        $reservation = Reservation::findOrFail($id);
        $price = $reservation->priceables()
                             ->whereUuid($price)
                             ->with('payments')
                             ->firstOrFail();

        return view('admin.reservations.prices.show', compact('reservation', 'price'));
    }
}
