<?php

namespace App\Models\v1;

use App\UuidForKey;
use Illuminate\Database\Eloquent\Model;

class Promotional extends Model
{
    use UuidForKey;

    protected $guarded = [];

    /**
     * The attributes that should be mutated to dates.
     * 
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'expires_at'];

    public function promoteable()
    {
        return $this->morphTo();
    }

    public function promocodes()
    {
        return $this->hasMany(Promocode::class);
    }
}
