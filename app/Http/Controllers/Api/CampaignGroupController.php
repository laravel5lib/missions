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
        $search = request()->get('search');
        $hasPublishedTrips = request()->get('hasPublishedTrips');

        $groups = Campaign::findOrFail($campaignId)
            ->groups()
            ->when($search, function ($query) use ($search) {
                return $query->where('name', 'LIKE', "%$search%");
            })
            ->when($hasPublishedTrips, function ($query) use ($campaignId) {
                return $query->whereHas('trips', function ($subQuery) use ($campaignId) {
                    return $subQuery->where('campaign_id', $campaignId)
                        ->public()
                        ->published();
                });
            })
            ->orderBy('name')
            ->paginate(request()->input('per_page', 25));

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
    public function update(CampaignGroupRequest $request, $campaignId, $groupId)
    {
        $campaign = Campaign::findOrFail($campaignId);
        $group = $campaign->groups()->findOrFail($groupId);

        $campaign->groups()->updateExistingPivot($groupId, [
            'status_id' => $request->input('status_id', $group->pivot->status_id),
            'meta' => $request->input('meta', $group->pivot->meta)
        ]);

        return new GroupResource($group->fresh());
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
