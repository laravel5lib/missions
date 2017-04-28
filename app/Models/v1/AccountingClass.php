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

    static public function get(Model $model)
    {
        if ($model instanceof Reservation) {
           $class = $this->firstOrCreate([
                'name' => $model->trip->campaign->fund->accountingClass->name.':Team'
            ]);

           return $class->id;
        }

        if ($model instanceof Trip) {
           $class = $this->firstOrCreate([
                'name' => $model->campaign->fund->accountingClass->name.':Team'
            ]);

           return $class->id;
        }
    }
}
