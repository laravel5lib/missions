<?php

namespace App\Http\Controllers\Api\Reporting;

use App\Http\Requests;
use App\Models\v1\User;
use App\Jobs\GenerateReport;
use App\Models\v1\Itinerary;
use Illuminate\Http\Request;
use App\Models\v1\Reservation;
use App\Http\Controllers\Controller;

class ReservationTravelController extends Controller
{   
    protected $reservation;
    protected $itinerary;
    protected $user;

    function __construct(Reservation $reservation, Itinerary $itinerary, User $user)
    {
        $this->reservation = $reservation;
        $this->itinerary = $itinerary;
        $this->user = $user;
    }

    public function store(Request $request)
    {
        $user = $this->user->findOrFail($request->get('author_id'));
        
        $reservations = $this->reservation
                             ->filter($request->all())
                             ->with('requirements.document')
                             ->get();

        $data = $this->columnize($reservations);

        $this->dispatch(new GenerateReport($data, 'reservation_travel', $user));

        return $this->response()->created(null, [
            'message' => 'Report is being generated and will be available shortly.'
        ]);
    }

    private function columnize($reservations)
    {

        return $reservations->map(function($reservation) {
            $data = [
                'Given Names' => $reservation->given_names,
                'Surname' => $reservation->surname,
                'Trip Rep' => $reservation->rep ? $reservation->rep->name : ($reservation->trip->rep ? $reservation->trip->rep->name : null),
                'Managing User' => $reservation->user->name,
                'Group' => $reservation->trip->group->name,
                'Trip Type' => $reservation->trip->type,
                'Campaign' => $reservation->trip->campaign->name,
                'designation' => $reservation->designation ? 
                    implode('', array_flatten($reservation->designation->content)) : 'none'
            ];

            $data = collect($data)
                        ->merge($this->passport($reservation))
                        ->merge($this->itinerary($reservation))
                        ->all();

            return $data;

        })->all();
    }

    private function passport($reservation)
    {
        $passport = $reservation->requirements->where('document_type', 'passports')->first()->document;

        return collect([
            'Passport Surname' => $passport ? $passport->surname : null,
            'Passport Given Names' => $passport ? $passport->given_names : null,
            'Passport Number' => $passport ? $passport->number : null,
            'Citizenship' => $passport ? country($passport->citizenship) : null,
            'Nationality' => $passport ? country($passport->birth_country) : null,
            'Expires' => $passport ? $passport->expires_at->toFormattedDateString() : null
        ]);
    }

    private function itinerary($reservation)
    {
        $itinerary_id = $reservation->requirements->where('document_type', 'travel_itineraries')->first()['document_id'];

        $itinerary = $this->itinerary
                          ->with('activities.type', 'activities.hubs', 'activities.transports')
                          ->find($itinerary_id);

        $travel = collect([
            'Arrival Method' => null,
            'Arrival Location' => null,
            'Arrival At' => null,
            'Arrival Transportation' => null,
            'Arrival Transport No.' => null,
            'Departure Method' => null,
            'Departure Location' => null,
            'Departure At' => null,
            'Departure Transportation' => null,
            'Departure Transport No.' => null,
        ]);

        if (!$itinerary) return $travel;

        $itinerary->activities->each(function($act) use($travel) {
            if ($act->type->name == 'arrival') {
                $travel['Arrival Method'] = $act->transports->first() ? $act->transports->first()->type : null;
                $travel['Arrival Location'] = $act->hubs->first() ? $act->hubs->first()->name : null;
                $travel['Arrival At'] = $act->occurred_at ? $act->occurred_at->toDateTimeString() : null;
                $travel['Arrival Transportation'] = $act->transports->first() ? $act->transports->first()->name : null;
                $travel['Arrival Transport No.'] = $act->transports->first() ? $act->transports->first()->vessel_no : null;
            }

            if ($act->type->name === 'departure') {
                 $travel['Departure Method'] = $act->transports->first() ? $act->transports->first()->type : null;
                 $travel['Departure Location'] = $act->hubs->first() ? $act->hubs->first()->name : null;
                 $travel['Departure At'] = $act->occurred_at ? $act->occurred_at->toDateTimeString() : null;
                 $travel['Departure Transportation'] = $act->transports->first() ? $act->transports->first()->name : null;
                 $travel['Departure Transport No.'] = $act->transports->first() ? $act->transports->first()->vessel_no : null;
            }
        });

        return $travel;
    }
}
