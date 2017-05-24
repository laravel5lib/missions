<?php

namespace App\Models\v1;

use Carbon\Carbon;
use App\UuidForKey;
use EloquentFilter\Filterable;
use App\Services\Teams\ValidatesSquads;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team extends Model
{
    use Filterable, UuidForKey, SoftDeletes;

    protected $guarded = [];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    public function type()
    {
        return $this->belongsTo(TeamType::class, 'type_id');
    }

    public function squads()
    {
        return $this->hasMany(TeamSquad::class);
    }

    public function groups()
    {
        return $this->morphedByMany(Group::class, 'teamable');
    }

    public function campaigns()
    {
        return $this->morphedByMany(Campaign::class, 'teamable');
    }

    public function validateSquads()
    {
        return new ValidatesSquads($this);
    }

    public function addTeamables(array $associations)
    {
        collect($associations)->each(function ($teamable) {
            $type = $teamable['type'];
            $id = $teamable['id'];

            $this->{$type}()->attach($id);
        });
    }
}
