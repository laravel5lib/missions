<?php

namespace App\Http\Controllers\Web\Dashboard;

use Illuminate\Http\Request;
use App\Models\v1\Reservation;
use App\Http\Controllers\Controller;

class ReservationRequirementController extends Controller
{
    public function index($reservationId)
    {
        $reservation = Reservation::findOrFail($reservationId);
        $requirements = $reservation->requireables()->get();
        $tab = 'requirements';
        $rep = $reservation->rep ? $reservation->rep : $reservation->trip->rep;

        $total = $requirements->count();
        $incomplete = $requirements->where('pivot.status', null)->count() + $requirements->where('pivot.status', 'incomplete')->count();
        $complete = $requirements->where('pivot.status', 'complete')->count();
        $reviewing = $requirements->where('pivot.status', 'reviewing')->count();
        $attention = $requirements->where('pivot.status', 'attention')->count();
        $percentage = $total > 0 ? ($complete/$total)*100 : 0;

        return view(
            'dashboard.reservations.requirements.index', 
            compact(
                'reservation', 
                'requirements', 
                'rep', 
                'tab', 
                'incomplete', 
                'complete', 
                'reviewing', 
                'attention', 
                'percentage', 
                'total'
            )
        );
    }

    public function show($reservationId, $requirementId)
    {
        $reservation = Reservation::findOrFail($reservationId);
        $requirement = $reservation->requireables()->findOrFail($requirementId);
        $tab = 'requirements';
        $rep = $reservation->rep ? $reservation->rep : $reservation->trip->rep;

        return view('dashboard.reservations.requirements.show', compact('reservation', 'requirement', 'rep', 'tab'));
    }
}
