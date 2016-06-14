<?php

namespace App\Models\v1;

use App\UuidForKey;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Prospect extends Model
{
    use Filterable, UuidForKey;
    
    protected $table = 'prospects';

    protected $fillable = [
        'name'
    ];
}
