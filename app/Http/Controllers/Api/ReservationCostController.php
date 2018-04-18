<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\v1\Reservation;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\CostResource;
use App\Http\Requests\v1\CostRequest;

class ReservationCostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($reservationId)
    {
        $costs = Reservation::findOrFail($reservationId)->prices()->paginate();
        
        return CostResource::collection($costs);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CostRequest $request, $reservationId)
    {
        Reservation::findOrFail($reservationId)->addPrice($request->all());

        return response()->json(['message' => 'New cost added to reservation.'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($reservationId, $id)
    {
        $cost = Reservation::findOrFail($reservationId)->prices()->findOrFail($id);

        return new CostResource($cost);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CostRequest $request, $reservationId, $id)
    {
        $cost = Reservation::findOrFail($reservationId)->costs()->findOrFail($id);

        $cost->update([
            'name' => $request->input('name', $cost->name),
            'amount' => $request->input('amount', $cost->amount),
            'type' => $request->input('type', $cost->type),
            'description' => $request->input('description', $cost->description),
            'active_at' => $request->input('active_at', $cost->active_at)
        ]);

        return new CostResource($cost);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($reservationId, $id)
    {
        $reservation = Reservation::findOrFail($reservationId);
        $cost = $reservation->prices()->findOrFail($id);

        DB::transaction(function() use($reservation, $cost) {
            $reservation->prices()->detach($cost->id);

            if ($cost->cost_assignable_id === $reservation->id && $cost->cost_assignable_type === 'reservations') {
                $cost->delete();
            }
        });

        return response()->json([], 204);
    }
}
