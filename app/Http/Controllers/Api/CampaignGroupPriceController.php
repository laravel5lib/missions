<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\v1\CampaignGroup;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\PriceResource;
use App\Http\Requests\v1\PriceRequest;

class CampaignGroupPriceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($groupId)
    {   
        $prices = CampaignGroup::whereUuid($groupId)
            ->firstOrFail()
            ->prices()
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
    public function store($groupId, PriceRequest $request)
    {
        $price = CampaignGroup::whereUuid($groupId)
            ->firstOrFail()
            ->prices()
            ->create([
                'cost_id' => $request->input('cost_id'),
                'amount' => $request->input('amount'),
                'active_at' => $request->input('active_at')
            ]);
        
        if ($request->filled('payments')) {
            $price->syncPayments($request->input('payments'));
        }

        return response()->json(['message' => 'New price added to group.'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $groupId
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($groupId, $uuid)
    {
        $price = CampaignGroup::whereUuid($groupId)
            ->firstOrFail()
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
     * @param  string $groupId
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function update(PriceRequest $request, $groupId, $uuid)
    {
        $price = CampaignGroup::whereUuid($groupId)
            ->firstOrFail()
            ->prices()
            ->whereUuid($uuid)
            ->firstOrFail();

        $price->update([
            'cost_id' => $request->input('cost_id', $price->cost_id),
            'amount' => $request->input('amount', $price->amount),
            'active_at' => $request->input('active_at', $price->active_at)
        ]);

        if ($request->filled('payments')) {
            $price->syncPayments($request->input('payments'));
        }

        return new PriceResource($price);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $groupId
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($groupId, $uuid)
    {
        $price = CampaignGroup::whereUuid($groupId)
            ->firstOrFail()
            ->prices()
            ->whereUuid($uuid)
            ->firstOrFail();

        $price->delete();

        return response()->json([], 204);
    }
}
