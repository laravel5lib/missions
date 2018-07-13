<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\v1\Reservation;
use App\Http\Controllers\Controller;

class ReservationRequirementController extends Controller
{
    public function create($reservationId)
    {
        $reservation = Reservation::findOrFail($reservationId);

        return view('admin.reservations.requirements.create', compact('reservation'));
    }

    public function show($reservationId, $requirementId)
    {
        $reservation = Reservation::findOrFail($reservationId);

        $requirement = $reservation->requireables()->findOrFail($requirementId);
        
        return view('admin.reservations.requirements.show', compact('reservation', 'requirement'));
    }

    public function edit($reservationId, $requirementId)
    {
        $reservation = Reservation::findOrFail($reservationId);

        $requirement = $reservation->requireables()->findOrFail($requirementId);
        
        return view('admin.reservations.requirements.edit', compact('reservation', 'requirement'));
    }
}
