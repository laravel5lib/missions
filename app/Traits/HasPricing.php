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
     * @param [type] $request
     * @return void
     */
    public function addPrice($request)
    {
        if ($request->input('cost_id')) {
            return $this->attachCostToModel($request->input('cost_id'));
        }

        return $this->createNewCostAndAttachToModel($request);
    }

    /**
     * Create new cost and attach it to the model
     *
     * @param [type] $request
     * @return void
     */
    private function createNewCostAndAttachToModel($request)
    {
        return DB::transaction(function() use($request) {
            $cost = $this->costs()->create([
                'name' => $request->input('name'),
                'amount' => $request->input('amount'),
                'type' => $request->input('type'),
                'description' => $request->input('description'),
                'active_at' => $request->input('active_at')
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