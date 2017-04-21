<?php

namespace App\Models\v1;

use App\UuidForKey;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Airport extends Model
{
    use UuidForKey, Filterable;

    protected $table = 'airports';
}
