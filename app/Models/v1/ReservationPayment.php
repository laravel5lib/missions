<?php

namespace App\Models\v1;


use Carbon\Carbon;
use Illuminate\Support\Collection;

class ReservationPayment {

    /**
     * @var Reservation
     */
    private $reservation;

    /**
     * ReservationPayment constructor.
     * @param Reservation $reservation
     */
    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    /**
     * Synchronize all the reservation's due payments.
     */
    public function sync()
    {
        if($this->reservation->has('dues')) $this->reservation->dues()->delete();

        $this->addDues($this->calculateDues());

        $this->updateBalances($this->reservation->fund->balance);
    }

    /**
     * Calculate the reservation's due payments.
     *
     * @return mixed
     */
    public function calculateDues()
    {
        // generate dues based on assigned costs
        $dues = $this->reservation->costs()
            ->with('payments')
            ->get()
            ->flatMap(function ($cost) {
                return $cost->payments->map(function ($payment) {
                    return [
                        'payment_id' => $payment->id,
                        'due_at' => $payment->due_at,
                        'grace_period' => $payment->grace_period,
                        'outstanding_balance' => $payment->amount_owed
                    ];
                })->all();
            });

        return $dues;
    }

    /**
     * Add dues to the reservation.
     *
     * @param $dues
     */
    public function addDues($dues)
    {
        if ( ! $dues) return;

        if ( ! $dues instanceof Collection)
            $dues = collect($dues);

        // get lowest due_at in dues,
        $min = $dues->reject(function($due) {
            return is_null($due['due_at']);
        })->min('due_at');

        // subtract a day from that date and apply as date for nulls
        $upfrontDate = is_null($min) ? Carbon::now() : $min->subDay();

        $data = $dues->map(function($due) use($upfrontDate) {
            
            $date = $upfrontDate->isPast() ? $upfrontDate : Carbon::now();

            is_null($due['due_at']) ? $due['due_at'] = $date : $due['due_at'];

            return new Due($due);
            
        })->all();

        $this->reservation->dues()->saveMany($data);
    }

    /**
     * Reconcile the outstanding balances.
     */
    public function reconcile()
    {
//        $amount = $this->reservation->fund->balance;
//
//        $this->updateBalances($amount);
    }

    /**
     * Update the reservation's outstanding balances.
     *
     * @param $amount
     */
    public function updateBalances($amount)
    {
        // Fund balance should be spread out over the fund's balance
        // needs to decrement or increment a due's balance based on changes to the fund's balance.
        while ($amount <> 0)
        {
            $due = $this->reservation->dues()
                ->withBalance()
                ->sortRecent()
                ->first();

            if (! $due) break;

            if ($due->outstanding_balance < $amount) {
                $carryOver = $amount - $due->outstanding_balance;
                $payment = $due->outstanding_balance;
            } else {
                $carryOver = 0;
                $payment = $amount;
            }

            $due->updateBalance($payment);

            $amount = $carryOver;
        }
    }

    /**
     * Get late payments.
     *
     * @return mixed
     */
    public function late()
    {
        return $this->reservation->dues()->with('payment')->get()->filter(function($due) {
           return $due->getStatus() == 'overdue';
           // && $due->payment->enforced
        });
    }

    /**
     * Bump to next incremental cost.
     *
     * @return bool
     */
    public function bump()
    {
        $current = $this->reservation
                        ->costs()
                        ->whereIn('id', $this->late()->pluck('payment.cost_id'))
                        ->get()
                        ->reject(function($cost) {
                            return  $cost->pivot->locked
                                    and $cost->type == 'incremental';
                        });

        if ($current->contains('type', 'incremental')) {
            $this->reservation->updateCosts();

            return true;
        }

        return false;
    }
}