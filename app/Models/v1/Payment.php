<?php

namespace App\Models\v1;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{   
    /**
     * Boot the Uuid trait for the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Uuid::uuid4();
        });
    }
    
    protected $table = 'price_payments';

    protected $fillable = [
        'percentage_due', 'due_at', 'grace_days'
    ];

    protected $dates = ['due_at'];

    public $timestamps = false;

    public function isPastDue()
    {
        return $this->due_at->addDays($this->grace_days)->isPast();
    }

    public function setDueAtAttribute($value)
    {
        $this->attributes['due_at'] = is_string($value) ? Carbon::parse($value)->endOfDay() : $value;
    }
}
