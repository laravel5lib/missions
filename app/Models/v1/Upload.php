<?php

namespace App\Models\v1;

use App\UuidForKey;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    use UuidForKey, Filterable;

    protected $fillable = [
        'id', 'source', 'name', 'type'
    ];

    /**
     * Get all of the upload's tags.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

}

