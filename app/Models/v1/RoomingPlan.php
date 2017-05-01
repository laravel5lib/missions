<?php

namespace App\Models\v1;

use App\UuidForKey;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoomingPlan extends Model
{
    use UuidForKey, SoftDeletes, Filterable;
    
    protected $guarded = [];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    
    public function rooms()
    {
        return $this->morphToMany(Room::class, 'roomable');
    }
}
