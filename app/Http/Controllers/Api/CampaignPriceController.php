<?php

namespace App\Http\Controllers\Api;

use App\Models\v1\Campaign;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PriceResource;
use App\Http\Requests\v1\PriceRequest;

class CampaignPriceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($campaignId)
    {   
        $prices = Campaign::findOrFail($campaignId)->prices()->paginate();

        return PriceResource::collection($prices);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($campaignId, PriceRequest $request)
    {
        Campaign::findOrFail($campaignId)
            ->prices()
            ->create([
                'cost_id' => $request->input('cost_id'),
                'amount' => $request->input('amount'),
                'active_at' => $request->input('active_at')
            ]);

        return response()->json(['message' => 'New price added to campaign.'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $campaignId
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($campaignId, $uuid)
    {
        $price = Campaign::findOrFail($campaignId)
            ->prices()
            ->whereUuid($uuid)
            ->with('payments')
            ->firstOrFail();

        return new PriceResource($price);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\v1\PriceRequest  $request
     * @param  string $campaignId
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function update(PriceRequest $request, $campaignId, $uuid)
    {
        $price = Campaign::findOrFail($campaignId)->prices()->whereUuid($uuid)->firstOrFail();

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
     * @param  string  $campaignId
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($campaignId, $uuid)
    {
        $price = Campaign::findOrFail($campaignId)->prices()->whereUuid($uuid)->firstOrFail();

        $price->delete();

        return response()->json([], 204);
    }
}
