<?php

namespace App\Models\v1;

use App\Traits\HasParentModel;
use App\Models\v1\Essay;
use Illuminate\Database\Eloquent\Builder;

class InfluencerApplication extends Essay
{
    use HasParentModel;

    protected $attributes = [
        'subject' => 'Influencer'
    ];

    public function getMorphClass()
    {
        return 'influencer_applications';
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('type', function (Builder $builder) {
            $builder->where('subject', 'Influencer');
        });
    }
}
