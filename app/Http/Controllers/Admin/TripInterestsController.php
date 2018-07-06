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
            'unresponsive' => $interests->where('status', 'unresponsive')->count(),
            'declined' => $interests->where('status', 'declined')->count()
        ];

        $percentChanges = [
            'undecided' => $this->getMetrics($interests, $campaignId, 'undecided'),
            'converted' => $this->getMetrics($interests, $campaignId, 'converted'),
            'unresponsive' => $this->getMetrics($interests, $campaignId, 'unresponsive'),
            'declined' => $this->getMetrics($interests, $campaignId, 'declined'),
        ];
        
        return view('admin.trips.interests.index', compact('campaign', 'totals', 'percentChanges'));
    }

    private function getMetrics($interests, $campaignId, $status)
    {
        $previousSevenDayPeriod = TripInterest::campaign($campaignId)
            ->where('status', $status)
            ->whereBetween('updated_at', [today()->endOfDay()->subDays(17), today()->endOfDay()->subDays(7)])
            ->count();
        
        $pastSevenDayPeriod = TripInterest::campaign($campaignId)
            ->where('status', $status)
            ->whereBetween('updated_at', [today()->endOfDay()->subDays(6), today()->endOfDay()])
            ->count();

        if ($pastSevenDayPeriod == 0) return ['count' => 0, 'change' => 0]; // prevent division by zero

        return ['count' => $pastSevenDayPeriod, 'change' => round( (($previousSevenDayPeriod - $pastSevenDayPeriod) / $pastSevenDayPeriod) * 100, 1)];
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
