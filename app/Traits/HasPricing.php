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
        return $this->morphToMany(Price::class, 'priceable');
    }
    
    /**
     * Get the current rate for the model.
     *
     * @return void
     */
    public function getCurrentRate()
    {
        return $this->priceables()->whereHas('cost', function ($q) {

            return $q->type('incremental');

        })->whereHas('payments', function ($q) {

            return $q->whereDate('due_at', '>=', now());

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
    private function createNewPriceAndAttachToModel(array $data)
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
        });
    }

    /**
     * Attach price to the model.
     *
     * @param [type] $priceId
     * @return void
     */
    private function attachPriceToModel($priceId)
    {
        return $this->priceables()->attach($priceId);
    }
}