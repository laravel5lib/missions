<?php

namespace App\Http\Controllers\Api;

use App\Models\v1\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\CostResource;
use App\Http\Requests\v1\CostRequest;

class TripCostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $tripId)
    {
        $search = $request->input('search');

        $costs = Trip::findOrFail($tripId)
            ->prices()
            ->when($search, function ($query) use ($search) {
                return $query->where('name', 'LIKE', "$search");
            })
            ->orderBy('active_at')
            ->paginate();

        return CostResource::collection($costs);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\v1\CostRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CostRequest $request, $tripId)
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
     * @param  \App\Http\Requests\v1\CostRequest  $request
     * @param  string $tripId
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function update(CostRequest $request, $tripId, $id)
    {
        $cost = Trip::findOrFail($tripId)->costs()->findOrFail($id);

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
