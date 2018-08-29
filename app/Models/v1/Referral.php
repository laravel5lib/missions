<?php

namespace App\Models\v1;

use App\UuidForKey;
use App\Traits\Manageable;
use Illuminate\Database\Eloquent\Model;
use App\Traits\InteractsWithReservations;

class Referral extends Model
{
    use UuidForKey, InteractsWithReservations, Manageable;

    protected $fillable = [
        'user_id', 
        'applicant_name', 
        'attention_to', 
        'recipient_email', 
        'type', 
        'referrer', 
        'response', 
        'sent_at', 
        'responded_at'
    ];

    protected $dates = ['created_at', 'updated_at', 'sent_at', 'responded_at'];

    protected $casts = ['response' => 'json', 'referrer' => 'json'];

    protected $appends = ['status'];

    /**
     * Get the status attribute.
     * 
     * @param  string $value
     * @return string
     */
    public function getStatusAttribute($value)
    {
        return $this->responded_at ? 'received' : ($this->sent_at ? 'sent' : 'draft');
    }

    /**
     * Get the user the referral belongs to.
     * 
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to the given status.
     * 
     * @param  Illuminate\Database\Eloquent\Builder $query
     * @param  String $status
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeStatus($query, $status)
    {
        if (! in_array($status, ['draft', 'sent', 'received'])) {
            return $this;
        }

        return $this->{$status}();
    }

    /**
     * Scope a query to draft status.
     * 
     * @param  Illuminate\Database\Eloquent\Builder $query
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeDraft($query)
    {
        return $query->whereNull('sent_at')->whereNull('responded_at');
    }

    /**
     * Scope a query to sent status.
     * 
     * @param  Illuminate\Database\Eloquent\Builder $query
     * @return Illuminate\Database\Eloquent\Builder       
     */
    public function scopeSent($query)
    {
        return $query->whereNotNull('sent_at')->whereNull('responded_at');
    }

    /**
     * Scope a query to received status.
     * 
     * @param  Illuminate\Database\Eloquent\Builder $query
     * @return Illuminate\Database\Eloquent\Builder       
     */
    public function scopeReceived($query)
    {
        return $query->whereNotNull('responded_at');
    }
}
