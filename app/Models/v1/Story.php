<?php

namespace App\Models\v1;

use App\UuidForKey;
use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    use UuidForKey;

    protected $fillable = ['title', 'content'];

    public function author()
    {
        return $this->morphTo();
    }
}
