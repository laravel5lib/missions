<?php

namespace App\Models\v1;

use App\UuidForKey;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    use UuidForKey;

    protected $fillable = [
        'name', 'url'
    ];

    public function linkable()
    {
        return $this->morphTo();
    }
}
