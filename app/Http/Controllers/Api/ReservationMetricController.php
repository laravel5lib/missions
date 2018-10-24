<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\v1\CampaignGroup;
use App\Http\Controllers\Controller;

class ReservationMetricController extends Controller
{
    public function percentOfCommitment(Request $request)
    {
        return ['data' => CampaignGroup::getPercentagesOfCommitments()];
    }
}
