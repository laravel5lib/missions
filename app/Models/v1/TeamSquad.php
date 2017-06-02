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

    public function isLastMemberOfTravelGroup($memberId)
    {
        $member = $this->members()->whereId($memberId)->first();

        $squads = $this->team->squads()->with('members.trip')->get();

        $groupIds = $squads->map(function ($squad) {
            return $squad->members->pluck('trip.group_id')->all();
        })->collapse()->all();

        // if not the last member in the entire team and the last one in the group then "yes"
        return $squads->pluck('members')->collapse()->count() == 1 || count($groupIds) > 1 ? false : true;
    }
}
