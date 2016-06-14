<?php

namespace App\Models\v1\Interaction;

use App\Models\v1\Region;
use App\Models\v1\TeamMember;
use App\UuidForKey;
use Carbon\Carbon;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use Filterable, UuidForKey;

    protected $fillable = [
        'id', 'team_member_id', 'region_id', 'name', 'gender', 'age_group',
        'phone', 'email', 'lat', 'long', 'treatments', 'party_size', 'decision', 'treatments'
    ];

    protected $dates = [ 'created_at', 'updated_at' ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'treatments' => 'array',
    ];

    public function member()
    {
        return $this->belongsTo(TeamMember::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function scopeYesterday($q)
    {
        return $q->whereBetween('created_at', [
            Carbon::yesterday()->startOfDay(),
            Carbon::yesterday()->endOfDay()
        ]);
    }
}
