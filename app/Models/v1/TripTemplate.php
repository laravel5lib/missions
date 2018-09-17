<?php

namespace App\Models\v1;

use App\UuidForKey;
use App\Traits\HasTags;
use App\Models\v1\Campaign;
use Illuminate\Database\Eloquent\Model;

class TripTemplate extends Model
{
    use UuidForKey, HasTags;

    protected $fillable = [
        'name', 'campaign_id', 'closed_at', 'companion_limit',
        'country_code', 'description', 'difficulty', 'ended_at',
        'prospects', 'spots', 'started_at', 'team_roles', 'todos',        
        'type'
    ];

    protected $dates = ['closed_at', 'created_at', 'updated_at', 'started_at', 'ended_at'];

    protected $casts = ['todos' => 'array', 'team_roles' => 'array', 'prospects' => 'array'];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}
