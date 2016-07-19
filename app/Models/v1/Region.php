<?php

namespace App\Models\v1;

use App\UuidForKey;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use Filterable, UuidForKey;

    protected $fillable = [
      'name', 'country_code', 'call_sign', 'campaign_id', 'is_hub'
    ];

    protected $dates = [
        'created_at', 'updated_at'
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    public function accommodations()
    {
        return $this->hasMany(Accommodation::class);
    }

    public function decisions()
    {
        return $this->hasMany(Interaction\Decision::class);
    }

    public function exams()
    {
        return $this->hasMany(Interaction\Exam::class);
    }

    public function sites()
    {
        return $this->hasMany(Interaction\Site::class);
    }

    /**
     * Get all of the region's tags.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
