<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\v1\CampaignGroup;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;

class ReservationMetricController extends Controller
{
    public function percentOfCommitment(Request $request)
    {
        $query = CampaignGroup::query();

        $results = QueryBuilder::for($query)
            ->allowedFilters(['commitment', 'campaign_id'])
            ->with(['organization.trips'])
            ->get()
            ->map(function ($group) {
                $reservationCount = $group->reservationsCount();

                $percentage = ($reservationCount == 0 || $group->commitment == 0) 
                    ? 0 
                    : round(($reservationCount/$group->commitment) * 100);

                return [
                    'id' => $group->id,
                    'group' => $group->organization,
                    'commitment' => $group->commitment,
                    'reservations' => $reservationCount,
                    'percentage' => $percentage
                ];
            })
            ->all();

        return ['data' => $results];
    }
}
