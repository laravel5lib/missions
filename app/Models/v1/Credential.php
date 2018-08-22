<?php

namespace App\Models\v1;

use App\UuidForKey;
use App\Traits\Manageable;
use Illuminate\Database\Eloquent\Model;
use App\Traits\InteractsWithReservations;

class Credential extends Model
{
    use UuidForKey, InteractsWithReservations, Manageable;

    protected $fillable = [
        'type', 'applicant_name', 'content', 'expired_at', 'user_id'
    ];

    /**
     * Attributes that should be cast to date objects
     *
     * @var array
     */
    protected $dates = ['expired_at', 'created_at', 'updated_at'];

    /**
     * Attributes that should be cast to native types
     *
     * @var array
     */
    protected $casts = ['content' => 'array'];

    /**
     * Get the user of this credential
     *
     * @return Belongs To
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get medical credentials
     * @param  Builder $query
     * @return Builder
     */
    public function scopeMedical($query)
    {
        return $query->where('type', 'medical');
    }

    /**
     * Get media credentials
     * @param  Builder $query
     * @return Builder
     */
    public function scopeMedia($query)
    {
        return $query->where('type', 'media');
    }
    
    /**
     * Get the credential's uploads.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function uploads()
    {
        return $this->morphToMany(Upload::class, 'uploadable');
    }
}
