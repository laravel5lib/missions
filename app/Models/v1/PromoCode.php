<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    protected $guarded = [];

    public function endorser()
    {
        return $this->morphTo();
    }

    protected $dates = ['created_at', 'updated_at', 'expires_at'];
}
