<?php

namespace App\Models\v1;

use App\UuidForKey;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Airline extends Model
{
    use UuidForKey, Filterable;

    protected $table = 'airlines';

    /**
     * Indicates if the model should be timestamped
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        // scope that filters to show only active Airlines
        static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('active', 'Y');
        });
    }
}
