<?php

namespace App\Models\v1;

use App\UuidForKey;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use UuidForKey;

    protected $guarded = [];

    protected $dates = [
        'created_at', 'updated_at'
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function squads()
    {
        return $this->hasMany(Squad::class);
    }

    public function members()
    {
        return $this->hasManyThrough(SquadMember::class, Squad::class);
    }

    public function getAverageSquadSize()
    {
        return round($this->squads()->withCount('members')->pluck('members_count')->avg());
    }

    public function getPercentageOfAllMissionaries()
    {
        $allMissionaries = SquadMember::whereHas('squad.region', function ($region) {
            return $region->where('campaign_id', $this->campaign_id);
        })->count();

        $regionalMissionaries = $this->members()->count();

        if ($allMissionaries == 0) return 0;

        return round(($regionalMissionaries / $allMissionaries) * 100);
    }
}
