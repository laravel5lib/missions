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
            'received' => $this->getMetrics($interests, $campaignId, 'received'),
            'converted' => $this->getMetrics($interests, $campaignId, 'converted'),
            'unresponsive' => $this->getMetrics($interests, $campaignId, 'unresponsive'),
            'declined' => $this->getMetrics($interests, $campaignId, 'declined'),
        ];
        
        return view('admin.trips.interests.index', compact('campaign', 'totals', 'percentChanges'));
    }

    private function getMetrics($interests, $campaignId, $status)
    {
        $dateColumn = ($status == 'received') ? 'created_at' : 'updated_at';

        $original = TripInterest::campaign($campaignId)
            ->when($dateColumn == 'updated_at', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->whereBetween($dateColumn, [today()->startOfDay()->subDays(28), today()->endOfDay()->subDays(14)])
            ->count();
        
        $new = TripInterest::campaign($campaignId)
            ->when($dateColumn == 'updated_at', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->whereBetween($dateColumn, [today()->startOfDay()->subDays(13), today()->endOfDay()])
            ->count();

        if ($original == 0) return ['count' => $new, 'change' => 0]; // prevent division by zero

        return ['count' => $new, 'change' => round( (($original - $new) / $original) * 100, 1)];
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
