<?php

namespace App\Traits;

use App\Models\v1\Price;
use Illuminate\Support\Facades\DB;

trait HasPricing 
{
    /**
     * Get all the model's prices.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function prices()
    {
        return $this->morphMany(Price::class, 'model');
    }
    
    /**
     * Get all prices assigned to the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function priceables()
    {
        return $this->morphToMany(Price::class, 'priceable')->withPivot('locked');
    }
    
    /**
     * Get the current rate for the model.
     *
     * @return void
     */
    public function getCurrentRate()
    {
        $rate = $this->priceables()->whereHas('cost', function ($q) {

            return $q->type('incremental');

        })->whereHas('payments', function ($q) {

            return $q->whereDate('due_at', '>=', now());

        })->orderBy('active_at')->first();

        return $rate ?? $this->priceables()->whereHas('cost', function ($q) {

            return $q->type('incremental');

        })->first();
    }

    public function getUpcomingPayment()
    {
        $payments = optional($this->getCurrentRate())->payments;
        
        return $payments ? $payments->first() : null;
    }

    public function getUpcomingDeadline()
    {
        return optional($this->getUpcomingPayment())->due_at;
    }

    /**
     * Get all upfront costs for the model.
     *
     * @return void
     */
    public function getUpfrontCosts()
    {
        return $this->priceables()->whereHas('cost', function ($q) {
            
            return $q->whereType('upfront');

        })->get();
    }

    public function getStartingCostAttribute()
    {
        return optional($this->getCurrentRate())->amount + $this->getUpfrontCosts()->sum('amount');
    }

    /**
     * Add a new price to the model.
     *
     * @param array $price
     * @return void
     */
    public function addPrice(array $price)
    {
        if (isset($price['price_id'])) {

            $price = Price::whereUuid($price['price_id'])->firstOrFail();

            return $this->attachPriceToModel($price->id);
        }

        return $this->createNewPriceAndAttachToModel($price);
    }

    /**
     * Create new price and attach it to the model
     *
     * @param array $price
     * @return void
     */
    public function createNewPriceAndAttachToModel(array $data)
    {
        return DB::transaction(function() use($data) {
            $price = $this->prices()->create([
                'cost_id' => isset($data['cost_id']) ? $data['cost_id'] : null,
                'amount' => isset($data['amount']) ? $data['amount'] : null,
                'active_at' => isset($data['active_at']) ? $data['active_at'] : null,
            ]);

            if (isset($data['payments']) && ! empty($data['payments'])) {
                $price->syncPayments($data['payments']);
            }
            
            $this->attachPriceToModel($price->id);

            return $price;
        });
    }

    /**
     * Attach price to the model.
     *
     * @param [type] $priceId
     * @return void
     */
    public function attachPriceToModel($priceId)
    {
        return $this->priceables()->attach($priceId);
    }
    
    /**
     * Remove a price from the model.
     *
     * @param Price $price
     * @return void
     */
    public function removePrice(Price $price)
    {
        DB::transaction(function() use($price) {
            $this->priceables()->detach($price->id);

            if ($price->model_id === $this->id && $price->model_type === $this->getMorphClass()) {
                $price->delete();
            }
        });
    }

    /**
     * Lock the current rate for the model
     *
     * @return boolean
     */
    public function lockCurrentRate()
    {
        $price = $this->getCurrentRate();
        
        return $this->priceables()->updateExistingPivot($price->id, ['locked' => true]);
    }

    /**
     * Unlock the current rate for the model
     *
     * @return boolean
     */
    public function unlockCurrentRate()
    {
        $price = $this->getCurrentRate();
        
        return $this->priceables()->updateExistingPivot($price->id, ['locked' => false]);
    }
}