<?php

namespace App\models\v1\interaction;

use App\Models\v1\Team;
use App\UuidForKey;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Stat extends Model
{
    use UuidForKey, Filterable;

    protected $table = 'stats_regions';

    protected $fillable = ['type', 'team_id', 'totals'];

    protected $dates = [ 'created_at', 'updated_at' ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'totals' => 'array',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
