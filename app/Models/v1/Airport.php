<?php

namespace App\Models\v1;

use App\UuidForKey;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Airport extends Model
{
    use UuidForKey, Filterable;

    protected $table = 'airports';

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        // scope that filters to show only Airports with IATA code set
        static::addGlobalScope('hasIATA', function (Builder $builder) {
            $builder->where('iata', '<>', '')->whereNotNull('iata');
        });
    }

}
