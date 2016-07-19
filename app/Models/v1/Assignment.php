<?php

namespace App\models\v1;

use App\UuidForKey;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assignment extends Model
{
    use Filterable, UuidForKey, SoftDeletes;

    protected $fillable = [
        'user_id', 'given_names', 'surname', 'gender', 'status',
        'birthday', 'campaign_id', 'type'
    ];

    protected $dates = [
        'created_at', 'updated_at', 'birthday'
    ];

    public function membership()
    {
        return $this->morphOne(TeamMember::class, 'assignable');
    }

    /**
     * Get all of the assignment's tags.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
