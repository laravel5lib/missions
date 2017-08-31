<?php

namespace App\Models\v1;

use App\UuidForKey;
use Conner\Tagging\Taggable;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    use UuidForKey, Filterable, Taggable;

    protected $fillable = [
        'id', 'source', 'name', 'type', 'meta'
    ];

    protected $casts = [
        'meta' => 'array'
    ];

    protected $dates = ['created_at', 'updated_at'];

    /**
     * Set the upload's meta data.
     *
     * @param $value
     */
    public function setMetaAttribute($value)
    {
        $this->attributes['meta'] = json_encode($value);
    }

    /**
     * Get the upload's users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function users()
    {
        return $this->morphedByMany(User::class, 'uploadable');
    }

    /**
     * Get the upload's fundraisers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function fundraisers()
    {
        return $this->morphedByMany(Fundraiser::class, 'uploadable');
    }

    /**
     * Get a Random Banner
     *
     * @param  Builder $query
     * @return Builder
     */
    public function scopeRandomBanner($query, $tags = [])
    {
        return $query->where('type', 'banner')
                     ->withAllTags($tags)
                     ->inRandomOrder();
    }
}
