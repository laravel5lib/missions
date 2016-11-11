<?php

namespace App\models\v1;

use App\UuidForKey;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Essay extends Model
{
    use UuidForKey, SoftDeletes, Filterable;

    protected $guarded = [];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $casts = ['content' => 'array'];

    public function setContentAttribute($value)
    {
        $this->attributes['content'] = json_encode($value);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
