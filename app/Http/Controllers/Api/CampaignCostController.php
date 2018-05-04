<?php

namespace App\Http\Controllers\Api;

use App\Models\v1\Campaign;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CostResource;
use App\Http\Requests\v1\CostRequest;

class CampaignCostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($campaignId)
    {   
        $costs = Campaign::findOrFail($campaignId)
            ->costs()
            ->paginate();

        return CostResource::collection($costs);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($campaignId, CostRequest $request)
    {
        $price = Campaign::findOrFail($campaignId)
            ->costs()
            ->create([
                'name' => $request->input('name'),
                'type' => $request->input('type'),
                'description' => $request->input('description')
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
    public function show($campaignId, $costId)
    {
        $cost = Campaign::findOrFail($campaignId)
            ->costs()
            ->findOrFail($costId);

        return new CostResource($cost);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\v1\CostRequest  $request
     * @param  string $campaignId
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function update(CostRequest $request, $campaignId, $costId)
    {
        $cost = Campaign::findOrFail($campaignId)->costs()->findOrFail($costId);

        $cost->update([
            'name' => $request->input('name', $cost->name),
            'description' => $request->input('description', $cost->description)
        ]);

        return new CostResource($cost);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $campaignId
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($campaignId, $costId)
    {
        $cost = Campaign::findOrFail($campaignId)->costs()->findOrFail($costId);

        $cost->delete();

        return response()->json([], 204);
    }
}
