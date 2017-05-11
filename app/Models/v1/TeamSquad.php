<?php

namespace App\Models\v1;

use App\UuidForKey;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use App\Services\Teams\ValidatesMembers;

class TeamSquad extends Model
{
    use UuidForKey, Filterable;

    protected $guarded = [];

    public $timestamps = false;

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function members()
    {
        return $this->belongsToMany(Reservation::class, 'team_members')
                    ->withPivot('leader')
                    ->withTimestamps();
    }

    public function validateMembers(array $members)
    {
        return new ValidatesMembers(collect($members), $this);
    }
}
