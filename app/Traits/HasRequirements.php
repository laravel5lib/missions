<?php

namespace App\Traits;

use App\Models\v1\Requirement;

trait HasRequirements 
{
    /**
     * Get all the model's requirements.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function requirements()
    {
        return $this->morphMany(Requirement::class, 'requester');
    }
    
    /**
     * Get all requirements assigned to the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function requireables()
    {
        return $this->morphToMany(Requirement::class, 'requireable')->withTimestamps();
    }
}