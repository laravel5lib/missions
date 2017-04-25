<?php

namespace App\Models\v1;

use App\UuidForKey;
use Carbon\Carbon;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use Filterable, UuidForKey;

    protected $guarded = [];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at', 'published_at'
    ];

    public function groups()
    {
        // $this->belongsToMany(Group::class);
    }

    public function squads()
    {
        // $this->hasMany(TeamSquad::class);
    }

    public function members()
    {
        // $this->hasManyThrough(TeamMember::class, TeamSquad::class);
    }
}
