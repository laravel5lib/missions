<?php

namespace App\Models\v1;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class ProjectPayment
{

    /**
     * @var Project
     */
    private $project;

    /**
     * ProjectPayment constructor.
     * @param Project $project
     */
    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    /**
     * Synchronize all the project's due payments.
     */
    public function sync()
    {
        if ($this->project->has('dues')) {
            $this->project->dues()->delete();
        }

        $this->addDues($this->calculateDues());

        $this->updateBalances($this->project->fund->balance);
    }

    /**
     * Calculate the project's due payments.
     *
     * @return mixed
     */
    public function calculateDues()
    {
        // generate dues based on assigned costs
        $dues = $this->project->costs()
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
     * Add dues to the project.
     *
     * @param $dues
     */
    public function addDues($dues)
    {
        if (! $dues) {
            return;
        }

        if (! $dues instanceof Collection) {
            $dues = collect($dues);
        }

        $data = $dues->map(function ($due) {
            is_null($due['due_at']) ? $due['due_at'] = Carbon::now() : $due['due_at'];

            return new Due($due);
        })->all();

        $this->project->dues()->saveMany($data);
    }

    /**
     * Update the project's outstanding balances.
     *
     * @param $amount
     */
    public function updateBalances($amount)
    {
        // Fund balance should be spread out over the fund's balance
        // needs to decrement or increment a due's balance based on changes to the fund's balance.
        while ($amount <> 0) {
            $due = $this->project->dues()
                ->withBalance()
                ->sortRecent()
                ->first();

            if (! $due) {
                break;
            }

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
        return $this->project->dues()->with('payment')->get()->filter(function ($due) {
            return $due->getStatus() == 'overdue';
        });
    }

    /**
     * Bump to next incremental cost.
     *
     * @return bool
     */
    public function bump()
    {
        $current = $this->project
            ->costs()
            ->whereIn('id', $this->late()->pluck('payment.cost_id'))
            ->get()
            ->reject(function ($cost) {
                return  ! $cost->pivot->locked
                and $cost->type == 'incremental';
            });

        if (! $current->contains('type', 'incremental')) {
            $active = $this->project->initiative->activeCosts()->get();

            $maxDate = $active->where('type', 'incremental')->max('active_at');

            $costs = $active->reject(function ($value) use ($maxDate) {
                return $value->type == 'incremental' && $value->active_at < $maxDate;
            });

            $new_costs = $current->merge($costs);

            $this->project->syncCosts($new_costs);

            return true;
        }

        return false;
    }
}
