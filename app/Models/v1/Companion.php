<?php

namespace App\Models\v1;

use App\UuidForKey;
use Illuminate\Database\Eloquent\Model;

class Companion extends Model
{
    use UuidForKey;

    protected $guarded = [];

    public $timestamps = false;
}
