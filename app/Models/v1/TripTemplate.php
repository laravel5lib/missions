<?php

namespace App\Models\v1;

use App\UuidForKey;
use Illuminate\Database\Eloquent\Model;

class TripTemplate extends Model
{
    use UuidForKey;

    protected $fillable = [
        'name', 'campaign_id', 'closed_at', 'companion_limit',
        'country_code', 'description', 'difficulty', 'ended_at',
        'prospects', 'spots', 'started_at', 'team_roles', 'todos',        
        'type'
    ];

    protected $dates = ['closed_at', 'created_at', 'updated_at'];

    protected $casts = ['todos' => 'array', 'team_roles' => 'array', 'prospects' => 'array'];
}
