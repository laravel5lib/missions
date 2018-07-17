<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\v1\Reservation;
use App\Http\Controllers\Controller;

class ReservationDocumentController extends Controller
{
    public function store($reservationId, Request $request)
    {
        Reservation::findOrFail($reservationId)->addDocument($request->all());

        return response()->json(['message' => 'Document added.'], 201);
    }
}
