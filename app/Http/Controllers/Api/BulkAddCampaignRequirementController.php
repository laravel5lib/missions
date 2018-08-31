<?php

namespace App\Http\Controllers\Api;

use App\Models\v1\Campaign;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BulkAddCampaignRequirementController extends Controller
{
    /**
     * Bulk add a requirement to selected child resources.
     * 
     * @param  string $campaignId
     * @param  string $id
     * @return Response
     */
    public function __invoke($campaignId, $id)
    {
        $campaign = Campaign::findOrFail($campaignId);
        $requirement = $campaign->requireables()->findOrFail($id);

        if (request()->groups) {
            $campaign->groups->each(function ($group) use ($requirement, $campaign) {
                
                $group->pivot->addRequirement(['requirement_id' => $requirement->id]);

                if (request()->trips) {
                    $group->trips()->where('campaign_id', $campaign->id)->get()->each(function ($trip) use ($requirement) {
                        $trip->addRequirement(['requirement_id' => $requirement->id]);

                        if (request()->reservations) {
                            $trip->reservations->each(function ($reservation) use ($requirement) {
                                $reservation->addRequirementToReservation($requirement);
                            });
                        }
                    });
                }
            });
        }

        return response()->json(['message' => 'Requirement added.'], 201);
    }
}
