<?php

namespace App\Http\Controllers\Api\Reporting;

use App\Http\Requests;
use App\Models\v1\User;
use App\Jobs\GenerateReport;
use Illuminate\Http\Request;
use App\Models\v1\Reservation;
use App\Http\Controllers\Controller;

class ReservationRequirementsController extends Controller
{
    protected $reservation;
    protected $user;
    
    function __construct(Reservation $reservation, User $user)
    {
        $this->reservation = $reservation;
        $this->user = $user;
    }

    public function store(Request $request)
    {
        $user = $this->user->findOrFail($request->get('author_id'));
        
        $reservations = $this->reservation
                             ->filter($request->all())
                             ->with('requirements.requirement')
                             ->get();

        $data = $this->columnize($reservations);

        $this->dispatch(new GenerateReport($data, 'reservation_requirements', $user));

        return $this->response()->created(null, [
            'message' => 'Report is being generated and will be available shortly.'
        ]);
    }

    private function columnize($reservations)
    {
        $requirementColumns = $reservations->pluck('requirements')
             ->flatten()
             ->keyBy('requirement.name')
             ->map(function($data) {
                return null;
            })->all();

        return $reservations->map(function($reservation) use($requirementColumns) {
            $data = [
                'Given Names' => $reservation->given_names,
                'Surname' => $reservation->surname,
                'Trip Rep' => $reservation->rep ? $reservation->rep->name : $reservation->trip->rep->name,
                'Managing User' => $reservation->user->name,
                'Group' => $reservation->trip->group->name,
                'Trip Type' => $reservation->trip->type,
                'Campaign' => $reservation->trip->campaign->name,
            ];

            $data = collect($data)->merge($requirementColumns)->all();

            foreach($reservation->requirements as $req)
            {
                $data[$req->requirement->name] = $req->status;
            }

            return $data;

        })->all();
    }
}
