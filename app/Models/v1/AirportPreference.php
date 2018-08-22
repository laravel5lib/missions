<?php

namespace App\Models\v1;

use App\Traits\HasParentModel;
use App\Models\v1\Questionnaire;
use Illuminate\Database\Eloquent\Builder;

class AirportPreference extends Questionnaire
{
    use HasParentModel;

    protected $attributes = [
        'type' => 'airport_preference'
    ];

    public function getMorphClass()
    {
        return 'airport_preferences';
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('type', function (Builder $builder) {
            $builder->where('type', 'airport_preference');
        });
    }
}
