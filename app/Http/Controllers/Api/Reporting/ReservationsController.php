<?php

namespace App\Http\Controllers\Api\Reporting;

use App\Http\Requests;
use App\Models\v1\User;
use App\Jobs\GenerateReport;
use Illuminate\Http\Request;
use App\Models\v1\Reservation;
use App\Http\Controllers\Controller;

class ReservationsController extends Controller
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
                             ->with('user', 'trip.campaign', 'trip.group', 'rep')
                             ->get();

        $data = $this->columnize($reservations);

        $this->dispatch(new GenerateReport($data, 'reservation_basic', $user));

        return $this->response()->created(null, [
            'message' => 'Report is being generated and will be available shortly.'
        ]);
    }

    private function columnize($reservations)
    {
        return $reservations->map(function($reservation) {
            return [
                'given_names' => $reservation->given_names,
                'surname' => $reservation->surname,
                'gender' => $reservation->gender,
                'marital_status' => $reservation->status,
                'shirt_size' => shirtSize($reservation->shirt_size),
                'age' => $reservation->age,
                'birthday' => $reservation->birthday->format('M d, Y'),
                'email' => $reservation->email,
                'primary_phone' => $reservation->phone_one,
                'secondary_phone' => $reservation->phone_two,
                'street_address' => $reservation->address,
                'city' => $reservation->city,
                'state_providence' => $reservation->state,
                'zip_postal' => $reservation->zip,
                'country' => country($reservation->country_code),
                'trip_rep' => $reservation->rep ? $reservation->rep->name : ($reservation->trip->rep ? $reservation->trip->rep->name : null),
                'managing_user' => $reservation->user->name,
                'user_email' => $reservation->user->email,
                'user_primary_phone' => $reservation->user->phone_one,
                'user_secondary_phone' => $reservation->user->secondary_phone,
                'group' => $reservation->trip->group->name,
                'trip_type' => $reservation->trip->type,
                'campaign' => $reservation->trip->campaign->name,
                'country_located' => country($reservation->trip->campaign->country_code),
                'start_date' => $reservation->trip->started_at->format('M d, Y'),
                'end_date' => $reservation->trip->ended_at->format('M d, Y'),
                'desired_role' => teamRole($reservation->desired_role),
                'designation' => $reservation->designation ? 
                    implode('', array_flatten($reservation->designation->content)) : 'none'
            ];
        })->all();
    }
}
