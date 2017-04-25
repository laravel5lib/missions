<?php

namespace App\Models\v1;

use App\UuidForKey;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class TeamSquad extends Model
{
    use UuidForKey, Filterable;

    protected $guarded = [];

    public $timestamps = false;

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
