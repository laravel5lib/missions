<?php

namespace App\Models\v1;

use App\UuidForKey;
use App\RoomTypeRules;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoomType extends Model
{
    use UuidForKey, SoftDeletes, Filterable;

    /**
     * Attributes that should not be mass assigned.
     * 
     * @var array
     */
    protected $guarded = [];

    /**
     * Arrtributes that should be cast as date time objects.
     * 
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * Attributes that should be cast to native types.
     * 
     * @var array
     */
    protected $casts = ['rules' => 'array'];

    /**
     * Get the rules for the room type.
     * 
     * @return App\RoomTypeRules
     */
    public function rules()
    {
        return new RoomTypeRules($this->rules);
    }

    public function plans()
    {
        return $this->belongsToMany(RoomingPlan::class, 'rooming_plan_room_type')
                    ->withPivot('available_rooms')
                    ->withTimestamps();
    }

    public function accommodations()
    {
        return $this->belongsToMany(Accommodation::class, 'accommodation_room_type')
                    ->withPivot('available_rooms')
                    ->withTimestamps();
    }

    public function setRulesAttribute(array $value)
    {
        $this->attributes['rules'] = json_encode($value);
    }

    public function getRulesAttribute($value)
    {
        return $value ? (array) json_decode($value) : [];
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = snake_case($value);
    }

    public function getNameAttribute($value)
    {
        return ucwords(str_replace('_', ' ', $value));
    }

}
