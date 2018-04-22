<?php

namespace App\Http\Controllers\Api;

use App\Models\v1\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\PriceResource;
use App\Http\Requests\v1\PriceRequest;

class TripPriceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $tripId)
    {
        $search = $request->input('search');
        $type = $request->input('type');

        $prices = Trip::findOrFail($tripId)
            ->priceables()
            ->when($search, function ($query) use ($search) {
                return $query->whereHas('cost', function ($subQuery) use ($search) {
                    return $subQuery->where('name', 'LIKE', "$search");
                });
            })
            ->when($type, function ($query) use ($type) {
                return $query->whereHas('cost', function ($subQuery) use ($type) {
                    return $subQuery->where('type', $type);
                });
            })
            ->orderBy('active_at')
            ->paginate();

        return PriceResource::collection($prices);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\v1\PriceRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PriceRequest $request, $tripId)
    {
        $trip = Trip::findOrFail($tripId)->addPrice($request->all());
        
        return response()->json(['message' => 'New trip price added.'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($tripId, $uuid)
    {
        $price = Trip::findOrFail($tripId)
            ->priceables()
            ->whereUuid($uuid)
            ->firstOrFail();

        return new PriceResource($price);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\v1\PriceRequest  $request
     * @param  string $tripId
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function update(PriceRequest $request, $tripId, $uuid)
    {
        $price = Trip::findOrFail($tripId)
            ->prices()
            ->whereUuid($uuid)
            ->firstOrFail();

        $price->update([
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
    public function destroy($tripId, $uuid)
    {
        $trip = Trip::findOrFail($tripId);
        $price = $trip->priceables()->whereUuid($uuid)->firstOrFail();

        DB::transaction(function() use($trip, $price) {
            $trip->priceables()->detach($price->id);

            if ($price->model_id === $trip->id && $price->model_type === 'trips') {
                $price->delete();
            }
        });

        return response()->json([], 204);
    }
}
