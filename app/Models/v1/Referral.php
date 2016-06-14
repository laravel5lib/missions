<?php

namespace App\Models\v1;

use App\UuidForKey;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    use Filterable, UuidForKey;
    
    protected $table = 'referrals';

    protected $fillable = [
        'name', 'user_id', 'referral_name', 'referral_email', 'referral_phone',
        'status', 'response_type', 'response_id', 'sent_at'
    ];

    protected $hidden = [];

    protected $dates = ['created_at', 'updated_at', 'sent_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function response()
    {
        return $this->morphTo();
    }
}
