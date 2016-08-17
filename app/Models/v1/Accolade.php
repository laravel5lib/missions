<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Model;

class Accolade extends Model
{
    protected $fillable = ['display_name', 'name', 'items'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'items' => 'array'
    ];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    /**
     * Set the accolade's countries visited.
     *
     * @param $value
     */
    public function setCountriesVisitedAttribute($value)
    {
        $this->attributes['items'] = json_encode($value);
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = snake_case(trim($value));
    }

    /**
     * Get the accolade's recipient.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function recipient()
    {
        return $this->morphTo();
    }
}
