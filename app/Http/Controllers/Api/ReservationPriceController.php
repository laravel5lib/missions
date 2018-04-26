<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\v1\Reservation;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\PriceResource;
use App\Http\Requests\v1\PriceRequest;

class ReservationPriceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($reservationId)
    {
        $prices = Reservation::findOrFail($reservationId)
            ->priceables()
            ->with('payments')
            ->paginate();
        
        return PriceResource::collection($prices);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PriceRequest $request, $reservationId)
    {
        Reservation::findOrFail($reservationId)->addPrice($request->all());

        return response()->json(['message' => 'New price added to reservation.'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($reservationId, $uuid)
    {
        $price = Reservation::findOrFail($reservationId)
            ->priceables()
            ->whereUuid($uuid)
            ->firstOrFail();

        return new PriceResource($price);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PriceRequest $request, $reservationId, $uuid)
    {
        $price = Reservation::findOrFail($reservationId)
            ->prices()
            ->whereUuid($uuid)
            ->firstOrFail();

        $price->update([
            'cost_id' => $request->input('cost_id', $price->cost_id),
            'amount' => $request->input('amount', $price->amount),
            'active_at' => $request->input('active_at', $price->active_at)
        ]);

        return new PriceResource($price);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($reservationId, $uuid)
    {
        $reservation = Reservation::findOrFail($reservationId);
        $price = $reservation->priceables()->whereUuid($uuid)->firstOrFail();

        DB::transaction(function() use($reservation, $price) {
            $reservation->priceables()->detach($price->id);

            if ($price->model_id === $reservation->id && $price->model_type === 'reservations') {
                $price->delete();
            }
        });

        return response()->json([], 204);
    }
}
