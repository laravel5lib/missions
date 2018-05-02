<?php

namespace App\Http\Controllers\Api;

use App\Models\v1\Campaign;
use Illuminate\Http\Request;
use App\Models\v1\CampaignGroup;
use App\Http\Controllers\Controller;
use App\Http\Resources\GroupResource;
use App\Http\Requests\CampaignGroupRequest;

class CampaignGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($campaignId)
    {
        $groups = Campaign::findOrFail($campaignId)->groups()->orderBy('name')->paginate(20);

        return GroupResource::collection($groups);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CampaignGroupRequest $request, $campaignId)
    {
        CampaignGroup::create([
            'campaign_id' => $campaignId,
            'group_id' => $request->group_id,
            'status_id' => $request->status_id
        ]);
        
        return response()->json(['message' => 'Group added to campaign.'], 201);
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
