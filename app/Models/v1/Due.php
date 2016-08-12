<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Model;

class Due extends Model
{
    protected $primaryKey = null;

    public $incrementing = false;

    protected $table = 'dues';

    protected $fillable = [
        'reservation_id', 'payment_id', 'due_at', 'grace_period', 'outstanding_balance'
    ];

    protected $dates = ['due_at'];

    public $timestamps = false;

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function getStatus()
    {
        if ($this->due_at->isPast() and $this->outstanding_balance > 0) return 'late';
        if ($this->outstanding_balance == 0) return 'paid';

        return 'pending';
    }
}
