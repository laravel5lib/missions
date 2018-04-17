<?php

namespace App\Http\Controllers\Api;

use App\Models\v1\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\CostResource;

class TripCostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($tripId)
    {
        $costs = Trip::findOrFail($tripId)->prices()->paginate();

        return CostResource::collection($costs);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $tripId)
    {
        $trip = Trip::findOrFail($tripId)->addPrice($request);
        
        return response()->json(['message' => 'New trip cost added.'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($tripId, $id)
    {
        $cost = Trip::findOrFail($tripId)->prices()->findOrFail($id);

        return new CostResource($cost);
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
    public function destroy($tripId, $id)
    {
        $trip = Trip::findOrFail($tripId);
        $cost = $trip->prices()->findOrFail($id);

        DB::transaction(function() use($trip, $cost) {
            $trip->prices()->detach($cost->id);

            if ($cost->cost_assignable_id === $trip->id && $cost->cost_assignable_type === 'trips') {
                $cost->delete();
            }
        });

        return response()->json([], 204);
    }
}
