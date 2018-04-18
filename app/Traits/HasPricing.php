<?php

namespace App\Traits;

use App\Models\v1\Cost;
use Illuminate\Support\Facades\DB;

trait HasPricing 
{
    /**
     * Get all the model's costs.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function costs()
    {
        return $this->morphMany(Cost::class, 'cost_assignable');
    }
    
    /**
     * Get all costs assigned to the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function prices()
    {
        return $this->morphToMany(Cost::class, 'costable');
    }

    /**
     * Add a new price to the model.
     *
     * @param array $cost
     * @return void
     */
    public function addPrice(array $cost)
    {
        if (isset($cost['cost_id'])) {
            return $this->attachCostToModel($cost['cost_id']);
        }

        return $this->createNewCostAndAttachToModel($cost);
    }

    /**
     * Create new cost and attach it to the model
     *
     * @param array $cost
     * @return void
     */
    private function createNewCostAndAttachToModel(array $cost)
    {
        return DB::transaction(function() use($cost) {
            $cost = $this->costs()->create([
                'name' => isset($cost['name']) ? $cost['name'] : null,
                'amount' => isset($cost['amount']) ? $cost['amount'] : null,
                'type' => isset($cost['type']) ? $cost['type'] : null,
                'description' => isset($cost['description']) ? $cost['description'] : null,
                'active_at' => isset($cost['active_at']) ? $cost['active_at'] : null,
            ]);
            
            $this->attachCostToModel($cost->id);
        });
    }

    /**
     * Attach cost to the model.
     *
     * @param [type] $costId
     * @return void
     */
    private function attachCostToModel($costId)
    {
        return $this->prices()->attach($costId);
    }
}