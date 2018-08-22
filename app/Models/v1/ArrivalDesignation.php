<?php

namespace App\Models\v1;

use App\Traits\HasParentModel;
use App\Models\v1\Questionnaire;
use Illuminate\Database\Eloquent\Builder;

class ArrivalDesignation extends Questionnaire
{
    use HasParentModel;

    protected $attributes = [
        'type' => 'arrival_designation'
    ];

    public function getMorphClass()
    {
        return 'arrival_designations';
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('type', function (Builder $builder) {
            $builder->where('type', 'arrival_designation');
        });
    }
}
