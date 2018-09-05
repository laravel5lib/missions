<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\v1\CampaignGroup;
use App\Http\Controllers\Controller;

class BulkAddCampaignGroupRequirementController extends Controller
{
    /**
     * Bulk add a requirement to selected child resources.
     * 
     * @param  string $groupId
     * @param  string $id
     * @return Response
     */
    public function __invoke($groupId, $id)
    {
        $group = CampaignGroup::whereUuid($groupId)->firstOrFail();
        $requirement = $group->requireables()->findOrFail($id);

        if (request()->trips) {
            $group->organization->trips()->where('campaign_id', $group->campaign_id)->get()->each(function ($trip) use ($requirement) {
                $trip->addRequirement(['requirement_id' => $requirement->id]);

                if (request()->reservations) {
                    $trip->reservations->each(function ($reservation) use ($requirement) {
                        $reservation->addRequirementToReservation($requirement);
                    });
                }
            });
        }

        return response()->json(['message' => 'Requirement added.'], 201);
    }
}
