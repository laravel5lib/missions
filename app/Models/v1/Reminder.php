<?php

namespace App\Models\v1;

use Ramsey\Uuid\Uuid;
use App\Mail\FundraiserReminder;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            $model->attributes['uuid'] = (string) Uuid::uuid4();
        });
    }

    protected $fillable = ['email'];

    public function remindable()
    {
        return $this->morphTo();
    }

    public function send()
    {
        if ($this->remindable_type == 'fundraisers') {
            Mail::to($this->email)->send(new FundraiserReminder($this->remindable));
        }
    }
}
