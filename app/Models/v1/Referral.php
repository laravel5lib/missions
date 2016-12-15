<?php

namespace App\Models\v1;

use App\UuidForKey;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    use Filterable, UuidForKey;
    
    protected $table = 'referrals';

    protected $guarded = [];

    protected $hidden = [];

    protected $dates = ['created_at', 'updated_at', 'sent_at'];

    protected $casts = ['response' => 'json'];

    public function setResponseAttribute($value)
    {
        $this->attributes['response'] = json_encode($value);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
