<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\v1\Reservation;
use App\Http\Controllers\Controller;

class ReservationDocumentController extends Controller
{
    public function store($reservationId, $docType, Request $request)
    {
        Reservation::findOrFail($reservationId)->addDocument($docType, $request->all());

        return response()->json(['message' => 'Document added.'], 201);
    }

    public function destroy($reservationId, $docType, $docId)
    {
        Reservation::findOrFail($reservationId)->removeDocument($docType, $docId);

        return response()->json([], 204);
    }
}
