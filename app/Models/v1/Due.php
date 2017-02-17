<?php

namespace App\Models\v1;

use App\UuidForKey;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Due extends Model
{
    use UuidForKey, Filterable;

    protected $table = 'dues';

    protected $fillable = [
        'payable_id', 'payable_type', 'payment_id', 'due_at', 'grace_period', 'outstanding_balance'
    ];

    protected $dates = ['due_at'];

    public function outstandingBalanceInDollars()
    {
        return number_format($this->outstanding_balance/100, 2, '.', ''); // convert to dollars
    }

    /**
     * Get the due's payment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * Get the due's owning model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function payable()
    {
        return $this->morphTo();
    }

    /**
     * Get the due's current status.
     *
     * @return string
     */
    public function getStatus()
    {
        // make sure the date is compared with the user's timezone
        $timezone = $this->getTimezone();

        if ($this->due_at
            ->timezone($timezone)
            ->addDays($this->grace_period)
            ->isPast() and $this->outstanding_balance > 0)
            return 'overdue';

        if ($this->due_at->timezone($timezone)->isPast() and $this->outstanding_balance > 0)
            return 'late';

        if ($this->due_at > $this->payment->due_at 
                and $this->due_at->timezone($timezone)->isFuture() 
                and $this->outstanding_balance > 0
            )
            return 'extended';

        if ($this->outstanding_balance == 0)
            return 'paid';

        return 'pending';
    }

    /**
     * Return dues that are extended.
     * 
     * @param  $query
     * @param  Date $date
     * @return mixed
     */
    public function scopeExtended($query)
    {
        return $query->join('payments', 'dues.payment_id', '=', 'payments.id')
                     ->whereRaw('dues.due_at > payments.due_at')
                     ->whereRaw('dues.due_at > NOW()')
                     ->where('dues.outstanding_balance', '>', 0);
    }

    /**
     * Return dues that are pending.
     * 
     * @param  $query
     * @return mixed
     */
    public function scopePending($query)
    {
        return $query->whereRaw('due_at > NOW()')
                     ->where('outstanding_balance', '>', 0);
    }

    /**
     * Return dues that are overdue
     * 
     * @param  $query 
     * @return mixed   
     */
    public function scopeOverdue($query)
    {
        return $query->whereRaw('DATE_ADD(due_at,INTERVAL grace_period DAY) < NOW()')
                     ->where('outstanding_balance', '>', 0);
    }

    /**
     * Return dues that are late
     * 
     * @param  $query 
     * @return mixed   
     */
    public function scopeLate($query)
    {
        return $query->whereRaw('due_at < NOW()')
                     ->where('outstanding_balance', '>', 0);
    }

    /**
     * Return dues that are paid
     * 
     * @param  $query 
     * @return mixed   
     */
    public function scopePaid($query)
    {
        return $query->where('outstanding_balance', '=', 0);
    }

    /**
     * Update the due's outstanding balance (WIP)
     *
     * @param $payment_amount
     * @return $this
     */
    public function updateBalance($payment_amount)
    {
        $new_balance = $this->outstanding_balance - $payment_amount;

        $this->outstanding_balance = $new_balance;
        $this->save();

        return $this;
    }

    /**
     * Return dues with a balance.
     *
     * @param $query
     * @return mixed
     */
    public function scopeWithBalance($query)
    {
        return $query->where('outstanding_balance', '>', 0);
    }

    /**
     * Sort by most recent due dates.
     *
     * @param $query
     * @return mixed
     */
    public function scopeSortRecent($query)
    {
        return $query->orderBy('due_at', 'asc')
                     ->orderBy('outstanding_balance', 'asc');
    }

    /**
     * Get Timezone
     * @return String
     */
    private function getTimezone()
    {
        if($this->payable instanceof Project) {
            $timezone = $this->payable->sponsor->timezone;
        } else {
            $timezone = $this->payable->user->timezone;
        }

        return $timezone;
    }
}
