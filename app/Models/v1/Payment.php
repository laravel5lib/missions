<?php

namespace App\Models\v1;

use App\UuidForKey;
use Carbon\Carbon;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use Filterable, UuidForKey;
    
    protected $table = 'payments';

    protected $fillable = [
        'amount_owed', 'percent_owed', 'due_at', 'grace_period', 'upfront'
    ];

    protected $dates = ['due_at'];

    public $timestamps = false;

    public function cost()
    {
        return $this->belongsTo(Cost::class);
    }

    public function scopePast($query)
    {
        return $query->where('due_at', '<', Carbon::now());
    }

    public function getBalanceDue()
    {
        return $this->cost->getBalanceDue($this->due_at);
    }
}
