<?php

namespace App\Models\v1;

use App\UuidForKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoomType extends Model
{
    use UuidForKey, SoftDeletes;

    protected $guarded = [];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $casts = ['rules' => 'array'];

    public function setRulesAttribute($value)
    {
        $this->attributes['rules'] = json_encode($value);
    }
}
