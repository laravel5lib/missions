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
        'id', 'source', 'name', 'type'
    ];

}

