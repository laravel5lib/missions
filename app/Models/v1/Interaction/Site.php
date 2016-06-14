<?php

namespace App\Models\v1\Interaction;

use App\Models\v1\Region;
use App\Models\v1\TeamMember;
use App\UuidForKey;
use Carbon\Carbon;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use Filterable, UuidForKey;

    protected $fillable = [
        'team_member_id', 'region_id', 'call_sign', 'site_type',
        'total_reached', 'total_decisions', 'lat', 'long'
    ];

    protected $dates = [ 'created_at', 'updated_at'];

    public function member()
    {
        return $this->belongsTo(TeamMember::class, 'team_member_id');
    }
    
    public function region()
    {
        return $this->belongsTo(Region::class);
    }
    
    public function scopeYesterday($q)
    {
        return $q->whereBetween('created_at', [Carbon::yesterday()->startOfDay(), Carbon::yesterday()->endOfDay()]);
    }
}
