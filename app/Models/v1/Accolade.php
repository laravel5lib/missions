<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Model;

class Accolade extends Model
{
    /**
     * primaryKey
     *
     * @var integer
     * @access protected
     */
    protected $primaryKey = 'recipient_id';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Attributes that can be mass assigned.
     *
     * @var array
     */
    protected $fillable = ['display_name', 'name', 'items'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'items' => 'array'
    ];

    /**
     * The dates that should be cast as date objects.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    /**
     * Set the accolade's items.
     *
     * @param $value
     */
    public function setItemsAttribute($value)
    {
        $this->attributes['items'] = json_encode($value);
    }

    /**
     * Set the accolade's name.
     *
     * @param $value
     */
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
