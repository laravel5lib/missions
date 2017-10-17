<?php

namespace App\Jobs\Reports\Reservations;

use App\Jobs\Job;
use App\Models\v1\Reservation;
use App\Services\Reports\CSVReport;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class FundsReport extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $request;
    protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($request, $user)
    {
        $this->request = $request;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Reservation $reservation)
    {
        $reservations = $reservation->filter(array_filter($this->request))
                             ->with('costs', 'dues.payment.cost')
                             ->get();

        $data = $this->columnize($reservations);

        $filename = 'reservations_funds_' . time();

        (new CSVReport($data, $this->user))->make($filename)->notify();
    }

    private function columnize($reservations)
    {
        return $reservations->map(function ($reservation) {
            return [
                'given_names' => $reservation->given_names,
                'surname' => $reservation->surname,
                'trip_rep' => $reservation->rep ? $reservation->rep->name : ($reservation->trip->rep ? $reservation->trip->rep->name : null),
                'managing_user' => $reservation->user->name,
                'group' => $reservation->trip->group->name,
                'trip_type' => $reservation->trip->type,
                'campaign' => $reservation->trip->campaign->name,
                'goal_amount' => $reservation->totalCostInDollars(),
                'amount_raised' => $reservation->totalRaisedInDollars(),
                'outstanding' => $reservation->totalOwedInDollars(),
                'percent_raised' => $reservation->getPercentRaised().'%',
                'incremental_costs' => implode(", ", $reservation->costs()->type('incremental')->get()->map(function ($cost) {
                    return $cost->name . ' ($'.number_format($cost->amountInDollars(), 2).')';
                })->all()),
                'static_costs' => implode(", ", $reservation->costs()->type('static')->get()->map(function ($cost) {
                    return $cost->name . ' ($'.number_format($cost->amountInDollars(), 2).')';
                })->all()),
                'optional_costs' => implode(", ", $reservation->costs()->type('optional')->get()->map(function ($cost) {
                    return $cost->name . ' ($'.number_format($cost->amountInDollars(), 2).')';
                })->all()),
                'payments' => implode(", ", $reservation->dues->map(function ($due) {
                    return $due->payment->cost->name. ' [balance: $'.number_format($due->outstandingBalanceInDollars(), 2).'] ('.$due->getStatus().')';
                })->all())
            ];
        })->all();
    }
}
