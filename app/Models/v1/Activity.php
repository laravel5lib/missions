<?php

namespace App\Models\v1;

use App\UuidForKey;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use UuidForKey, SoftDeletes, Filterable;

    protected $guarded = [];

    protected $dates = ['occured_at', 'created_at', 'updated_at', 'deleted_at'];

    public function participant()
    {
        return $this->morphTo();
    }
}
