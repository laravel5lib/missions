<?php

namespace App\Models\v1;

use App\UuidForKey;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use Filterable, UuidForKey;

    protected $fillable = [
        'name', 'color'
    ];

    public $timestamps = false;
}
