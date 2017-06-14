<?php

namespace App\Models\v1;

use App\UuidForKey;
use App\TeamTypeRules;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class TeamType extends Model
{
    use UuidForKey, Filterable;
    
    /**
     * Attributes that should not be mass assigned.
     * 
     * @var array
     */
    protected $guarded = [];

    /**
     * Attributes that should be cast to native types.
     * 
     * @var array
     */
    protected $casts = ['rules' => 'array'];

    /**
     * Set the name attribute
     * 
     * @param string $value
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtolower( trim($value) );
    }

    /**
     * Set the rules attribute
     * 
     * @param array $value
     */
    public function setRulesAttribute($value)
    {
        $this->attributes['rules'] = json_encode($value);
    }

    /**
     * Get the rules attribute.
     * 
     * @param  $value
     * @return array
     */
    public function getRulesAttribute($value)
    {
        return $value ? (array) json_decode($value) : [];
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * Get teams
     * 
     * @return HasMany
     */
    public function teams()
    {
        return $this->hasMany(Team::class, 'type_id');
    }

    /**
     * Get rules
     * 
     * @return TeamTypeRules
     */
    public function rules()
    {
        return new TeamTypeRules($this->rules);
    }
}
