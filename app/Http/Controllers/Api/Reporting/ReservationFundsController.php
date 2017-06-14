<?php

namespace App\Http\Controllers\Api\Reporting;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\v1\Reservation;
use App\Http\Controllers\Controller;

class ReservationFundsController extends Controller
{
    function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function store(Request $request)
    {
        $reservations = $this->reservation
                             ->filter($request->all())
                             ->with('costs', 'dues.payment.cost')
                             ->get();

        $data = $this->columnize($reservations);

        return $data;

        // (new GenerateReport($filename)->create($data, $sheetname = 'Reservations');
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
