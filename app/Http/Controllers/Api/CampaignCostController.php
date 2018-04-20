<?php

namespace App\Http\Controllers\Api;

use App\Models\v1\Campaign;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\RateResource;
use App\Http\Requests\v1\RateRequest;

class CampaignCostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($campaignId)
    {   
        $costs = Campaign::findOrFail($campaignId)->costs()->paginate();

        return RateResource::collection($costs);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($campaignId, RateRequest $request)
    {
        $costs = Campaign::findOrFail($campaignId)
            ->costs()
            ->create([
                'name' => $request->input('name'),
                'amount' => $request->input('amount'),
                'type' => $request->input('type'),
                'description' => $request->input('description'),
                'active_at' => $request->input('active_at')
            ]);

        return response()->json(['message' => 'New cost added to campaign.'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $campaignId
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($campaignId, $id)
    {
        $cost = Campaign::findOrFail($campaignId)->costs()->findOrFail($id);

        return new RateResource($cost);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\v1\RateRequest  $request
     * @param  string $campaignId
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function update(RateRequest $request, $campaignId, $id)
    {
        $cost = Campaign::findOrFail($campaignId)->costs()->findOrFail($id);

        $cost->update([
            'name' => $request->input('name', $cost->name),
            'amount' => $request->input('amount', $cost->amount),
            'type' => $request->input('type', $cost->type),
            'description' => $request->input('description', $cost->description),
            'active_at' => $request->input('active_at', $cost->active_at)
        ]);

        return new RateResource($cost);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $campaignId
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($campaignId, $id)
    {
        $cost = Campaign::findOrFail($campaignId)->costs()->findOrFail($id);

        $cost->delete();

        return response()->json([], 204);
    }
}
