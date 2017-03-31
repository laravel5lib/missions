<?php

namespace App\Models\v1;

use App\UuidForKey;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Credential extends Model
{
    use UuidForKey, SoftDeletes, Filterable;

    protected $guarded = [];

    /**
     * Attributes that should be cast to date objects
     * 
     * @var array
     */
    protected $dates = ['expired_at', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * Attributes that should be cast to native types
     * 
     * @var array
     */
    protected $casts = ['content' => 'array'];

    /**
     * Set the content attribute
     * 
     * @param Array $value
     */
    public function setContentAttribute($value)
    {
        $this->attributes['content'] = json_encode($value);
    }

    /**
     * Get the holder of this credential
     * 
     * @return MorphTo
     */
    public function holder()
    {
        return $this->morphTo();
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
