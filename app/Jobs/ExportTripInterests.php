<?php

namespace App\Jobs;

use App\Models\v1\TripInterest;

class ExportTripInterests extends Exporter
{
    public function data(array $request)
    {
        $interests = TripInterest::filter($request)
            ->with('trip.group', 'trip.campaign')
            ->get();

        return $interests;
    }

    public function columns($interest)
    {
        return [
            'name' => $interest->name,
            'email' => $interest->email,
            'phone' => $interest->phone,
            'communication_preferences' => implode(', ', $interest->communication_preferences),
            'status' => $interest->status,
            'trip_type' => $interest->trip->type,
            'campaign' => $interest->trip->campaign->name,
            'group' => $interest->trip->group->name,
            'created_at' => $interest->created_at->toDateTimeString()
        ];
    }
}
