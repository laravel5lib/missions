<?php

namespace App\Http\Controllers\Api\Reporting;

use App\Http\Requests;
use App\Models\v1\User;
use App\Jobs\GenerateReport;
use Illuminate\Http\Request;
use App\Models\v1\Reservation;
use App\Http\Controllers\Controller;

class ReservationFundsController extends Controller
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
                             ->with('costs', 'dues.payment.cost')
                             ->get();

        $data = $this->columnize($reservations);

        $this->dispatch(new GenerateReport($data, 'reservation_funds', $user));

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
                'trip_rep' => $reservation->rep ? $reservation->rep->name : $reservation->trip->rep->name,
                'managing_user' => $reservation->user->name,
                'group' => $reservation->trip->group->name,
                'trip_type' => $reservation->trip->type,
                'campaign' => $reservation->trip->campaign->name,
                'goal_amount' => $reservation->totalCostInDollars(),
                'amount_raised' => $reservation->totalRaisedInDollars(),
                'outstanding' => $reservation->totalOwedInDollars(),
                'percent_raised' => $reservation->getPercentRaised().'%',
                'incremental_costs' => implode(", ", $reservation->costs()->type('incremental')->get()->map(function($cost) {
                    return $cost->name . ' ($'.number_format($cost->amountInDollars(),2).')';
                })->all()),
                'static_costs' => implode(", ", $reservation->costs()->type('static')->get()->map(function($cost) {
                    return $cost->name . ' ($'.number_format($cost->amountInDollars(),2).')';
                })->all()),
                'optional_costs' => implode(", ", $reservation->costs()->type('optional')->get()->map(function($cost) {
                    return $cost->name . ' ($'.number_format($cost->amountInDollars(),2).')';
                })->all()),
                'payments' => implode(", ", $reservation->dues->map(function($due) {
                    return $due->payment->cost->name. ' [balance: $'.number_format($due->outstandingBalanceInDollars(),2).'] ('.$due->getStatus().')';
                })->all())
            ];
        })->all();
    }
}
