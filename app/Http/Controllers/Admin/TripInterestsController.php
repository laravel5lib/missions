<?php

namespace App\Http\Controllers\admin;

use App\Models\v1\Campaign;
use App\Models\v1\TripInterest;
use App\Http\Controllers\Controller;

class TripInterestsController extends Controller
{
    public function index($campaignId)
    {
        $campaign = Campaign::findOrFail($campaignId);

        $interests = TripInterest::campaign($campaignId)->select('id', 'status')->get();

        $totals = [
            'undecided' => $interests->where('status', 'undecided')->count(),
            'converted' => $interests->where('status', 'converted')->count(),
            'declined' => $interests->where('status', 'declined')->count()
        ];
        
        return view('admin.trips.interests.index', compact('campaign', 'totals'));
    }

    public function show($campaignId, $id)
    {
        $campaign = Campaign::findOrFail($campaignId);
        $interest = TripInterest::findOrFail($id);

        return view('admin.trips.interests.show', compact('interest', 'campaign'));
    }

    public function edit($campaignId, $id)
    {
        $campaign = Campaign::findOrFail($campaignId);
        $interest = TripInterest::findOrFail($id);

        return view('admin.trips.interests.edit', compact('interest', 'campaign'));
    }
}
