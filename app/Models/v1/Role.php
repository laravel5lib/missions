<?php

namespace App\Models\v1;

use App\UuidForKey;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use Filterable, UuidForKey;
    
    protected $fillable = ['name'];

    public $timestamps = false;

    public function members()
    {
        return $this->hasMany(TeamMember::class);
    }
}
