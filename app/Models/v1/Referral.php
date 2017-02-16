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

    protected $dates = ['created_at', 'updated_at', 'sent_at', 'responded_at'];

    protected $casts = ['response' => 'json', 'referrer' => 'json'];

    protected $appends = ['status'];

    public function setResponseAttribute($value)
    {
        $this->attributes['response'] = json_encode($value);
    }

    public function setReferrerAttribute($value)
    {
        $this->attributes['referrer'] = json_encode($value);
    }

    public function getStatusAttribute($value)
    {
        return $this->responded_at ? 'received' : ($this->sent_at ? 'sent' : 'draft');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeDraft($query)
    {
        return $query->whereNull('sent_at')->whereNull('responded_at');
    }

    public function scopeSent($query)
    {
        return $query->whereNotNull('sent_at')->whereNull('responded_at');
    }

    public function scopeReceived($query)
    {
        return $query->whereNotNull('responded_at');
    }
}
