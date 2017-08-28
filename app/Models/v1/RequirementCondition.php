<?php

namespace App\Models\v1;

use App\UuidForKey;
use Illuminate\Database\Eloquent\Model;

class RequirementCondition extends Model
{
    use UuidForKey;
    
    protected $guarded = [];

    protected $casts = [
        'applies_to' => 'array'
    ];

    public function setAppliesToAttribute($value)
    {
        $applicable = collect($value)->transform(function ($item) {
            return trim(strtoupper($item));
        });
        
        $this->attributes['applies_to'] = json_encode($applicable);
    }

    public function requirement()
    {
        return $this->belongsTo(Requirement::class);
    }
}
