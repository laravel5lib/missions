<?php

namespace App\Http\Controllers\Api;

use App\Models\v1\Trip;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BulkAddTripRequirementController extends Controller
{
    /**
     * Bulk add a requirement to selected child resources.
     * 
     * @param  string $tripId
     * @param  string $id
     * @return Response
     */
    public function __invoke($tripId, $id)
    {
        $trip = Trip::findOrFail($tripId);
        $requirement = $trip->requireables()->findOrFail($id);

        if (request()->reservations) {
            $trip->reservations->each(function ($reservation) use ($requirement) {
                $reservation->addRequirementToReservation($requirement);
            });
        }

        return response()->json(['message' => 'Requirement added.'], 201);
    }
}
