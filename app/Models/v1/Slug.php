<?php

namespace App\Models\v1;

use App\UuidForKey;
use Illuminate\Database\Eloquent\Model;

class Slug extends Model
{
    use UuidForKey;

    protected $guarded = [];

    protected $dates = ['created_at', 'updated_at'];

    public function slugable()
    {
        return $this->morphto();
    }
}
