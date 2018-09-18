<?php

namespace App;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FilterTags implements Filter
{
    public function __invoke(Builder $query, $value, string $property) : Builder
    {
        return $query->whereHas('trip', function ($subQuery) use ($value) {
            return $subQuery->withAllTags($value, 'trip');
        });
    }
}