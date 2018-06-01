<?php

namespace App\Http\Controllers\Admin;

use App\Models\v1\Campaign;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FlightController extends Controller
{
    public function index($campaignId)
    {
        $campaign = Campaign::findOrFail($campaignId);

        return view('admin.flights.index', compact('campaign'));
    }
}
