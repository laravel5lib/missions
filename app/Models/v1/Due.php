<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Model;

class Due extends Model
{
//    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $table = 'dues';

    protected $fillable = [
        'payable_id', 'payable_type', 'payment_id', 'due_at', 'grace_period', 'outstanding_balance'
    ];

    protected $dates = ['due_at'];

    public $timestamps = false;

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
        $timezone = $this->reservation->user->timezone;

        if ($this->due_at
            ->timezone($timezone)
            ->addDays($this->grace_period)
            ->isPast() and $this->outstanding_balance > 0)
            return 'overdue';

        if ($this->due_at->timezone($timezone)->isPast() and $this->outstanding_balance > 0)
            return 'late';

        if ($this->due_at > $this->payment->due_at and $this->due_at->timezone($timezone)->isFuture())
            return 'extended';

        if ($this->outstanding_balance == 0)
            return 'paid';

        return 'pending';
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
        return $query->orderBy('due_at', 'asc');
    }
}
