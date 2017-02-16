<?php

namespace App\Models\v1;

use App\UuidForKey;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Questionnaire extends Model
{
    use UuidForKey, Filterable;

    protected $guarded = [];

    protected $casts = ['content' => 'array'];

    public function setContentAttribute($value)
    {
        $this->attributes['content'] = json_encode($value);
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}
