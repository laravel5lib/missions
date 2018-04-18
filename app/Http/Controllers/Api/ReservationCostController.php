<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\v1\Reservation;
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
        Reservation::findOrFail($reservationId)->addPrice($request);

        return response()->json(['message' => 'New cost added to reservation.'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
