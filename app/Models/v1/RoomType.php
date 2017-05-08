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
     * Set the rules attribute
     * 
     * @param array $value
     */
    public function setRulesAttribute(array $value)
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

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = snake_case($value);
    }

    public function getNameAttribute($value)
    {
        return ucwords(str_replace('_', ' ', $value));
    }

    /**
     * Get the rules for the room type.
     * 
     * @return App\RoomTypeRules
     */
    public function rules()
    {
        return new RoomTypeRules($this->rules);
    }
}
