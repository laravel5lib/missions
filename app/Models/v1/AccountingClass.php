<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Model;

class AccountingClass extends Model
{
    protected $guarded = [];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim($value);
    }

    public function funds()
    {
        return $this->hasMany(Fund::class, 'class_id');
    }

    /**
     * Generate a class name for the given model
     * 
     * @param  Model  $model
     * @return String
     */
    public function generateName(Model $model)
    {
        if ($model instanceof Reservation) {
            return $model->trip->campaign->name . ' - Team';
        }

        if ($model instanceof Trip) {
           return $model->campaign->name . ' - Team';
        }

        if ($model instanceof Campaign) {
            return $model->name . ' - General';
        }
    }
}
