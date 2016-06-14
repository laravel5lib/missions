<?php

namespace App\Models\v1\Interaction;

use App\Models\v1\Region;
use App\Models\v1\TeamMember;
use App\UuidForKey;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Decision extends Model
{
    use Filterable, UuidForKey;

    protected $fillable = [
        'team_member_id', 'region_id',  'name', 'gender',
        'age_group', 'phone', 'email', 'lat', 'long', 'decision', 'id'
    ];

    protected $dates = [ 'created_at', 'updated_at' ];

    public function member()
    {
        return $this->belongsTo(TeamMember::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}
