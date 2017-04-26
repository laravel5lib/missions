<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Model;

class AccountingItem extends Model
{
    protected $guarded = [];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim($value);
    }

    public function funds()
    {
        return $this->hasMany(Fund::class, 'item_id');
    }
}
