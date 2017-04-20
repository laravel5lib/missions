<?php

namespace App\Models\v1;

use App\UuidForKey;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Airline extends Model
{
    use UuidForKey, Filterable;

    protected $table = 'airlines';
}
