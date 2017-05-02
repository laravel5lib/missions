<?php

namespace App\Models\v1;

use App\UuidForKey;
use Illuminate\Database\Eloquent\Model;

class TeamType extends Model
{
    use UuidForKey;
    
    protected $guarded = [];

    protected $casts = ['rules' => 'array'];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtolower( trim($value) );
    }

    public function setRulesAttribute($value)
    {
        $this->attributes['rules'] = json_encode($value);
    }

    public function teams()
    {
        return $this->hasMany(Team::class, 'type_id');
    }
}
