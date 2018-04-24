<?php

namespace App\Http\Controllers\Admin;

use App\Models\v1\Trip;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TripPriceController extends Controller
{
    public function show($id, $price)
    {
        $trip = Trip::findOrFail($id);
        $price = $trip->priceables()->whereUuid($price)->firstOrFail();

        return view('admin.trips.tabs.prices.show', compact('trip', 'price'));
    }
}
