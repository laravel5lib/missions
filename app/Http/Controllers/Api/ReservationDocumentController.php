<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\v1\Reservation;
use App\Http\Controllers\Controller;
use App\Http\Resources\DocumentResource;

class ReservationDocumentController extends Controller
{
    public function index($reservationId, $docType)
    {
        $documents = Reservation::findOrFail($reservationId)->{camel_case($docType)}()->get();

        return DocumentResource::collection($documents);
    }

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
