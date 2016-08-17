<?php

namespace App\Models\v1;

use App\UuidForKey;
use Conner\Tagging\Taggable;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    use UuidForKey, Filterable, Taggable;

    protected $fillable = ['title', 'content'];

    public function author()
    {
        return $this->morphTo();
    }
}
