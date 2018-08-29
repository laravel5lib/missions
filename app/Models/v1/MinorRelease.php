<?php

namespace App\Models\v1;

use App\Traits\HasParentModel;
use App\Models\v1\Questionnaire;
use Illuminate\Database\Eloquent\Builder;

class MinorRelease extends Questionnaire
{
    use HasParentModel;

    protected $attributes = [
        'type' => 'minor_release'
    ];

    public function getMorphClass()
    {
        return 'minor_releases';
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('type', function (Builder $builder) {
            $builder->where('type', 'minor_release');
        });
    }
}
