<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\v1\CampaignGroup;
use Illuminate\Http\Request;

class CampaignGroupPriceableController extends Controller
{
    public function store($groupId, $priceId, Request $request)
    {
        $group = CampaignGroup::whereUuid($groupId)->firstOrFail();
        
        $group->trips->each(function ($trip), use ($priceId, $request) {
            $trip->addPrice(['price_id' => $priceId]);

            if ($request->input('with_reservations')) {
                $trip->reservations->each(function ($reservation), use ($priceId) {
                    $reservation->addPrice(['price_id' => $priceId]);
                });
            }
        });

        return response()->json(['Price added to trips.'], 201);
    }
}
