<?php

namespace App\Models\v1;

use App\UuidForKey;
use Illuminate\Database\Eloquent\Model;

class AccountingItem extends Model
{
    use UuidForKey;
    
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
