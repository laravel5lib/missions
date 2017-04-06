<?php

namespace App\Traits;

use App\Facades\Promocodes;
use App\Models\v1\Promocode;
use App\Models\v1\Promotional;

trait Promoteable
{
    /**
     * Create promocodes for current model.
     *
     * @param int  $amount
     * @param null $reward
     *
     * @return mixed
     */
    public function promote(
        $name, 
        $qty = 1, 
        $reward = null, 
        $expires = null,
        $affilates = null
    )
    {

        $promotionals = $this->promotionals()->create([
            'name' => $name,
            'reward' => $reward,
            'expires_at' => $expires
        ]);

        $promotionals->each(function ($promotional) use($qty) {
            
            $records = [];

            if ($affilates) {
                $this->{$affiliates}->each(function($affiliate) {
                    $affilate->createCode();
                });
            }

            // if the promoter has rewardable items
            // then we need to determine those items
            // and loop through them assign a unique promocode to them
            
            // $this->{$affiliate}()->each(){ $rewardable->createCode() }
            
            // other wise we just create promocodes without rewardable items

            // loop though each promocodes required
            foreach (Promocodes::output($qty) as $code) {
                $records[] = new Promocode([
                    'code'   => $code,
                    // 'rewardable_id' => $this->id,
                    // 'rewardable_type' => str_plural(get_class($this))
                ]);
            }

            // check for insertion of record
            if ($promotional->promocodes()->saveMany($records)) {
                return collect($records);
            }

            return collect([]);
        });
    }

    /**
     * Check the code against the current model.
     * 
     * @param  String $code
     * @return mixed
     */
    public function checkCode($code)
    {
        $promocode = Promocode::byCode($code)->first();

        if (! $promocode or ! $promocode->promotional) return false;

        $promoter = str_singular($promocode->promotional->promoteable_type);

        if (! $promoter) return $promocode;

        if (method_exists($this, $promoter)) {
            if ($this->{$promoter}()->where('id', $promocode->promotional->promoteable_id)->exists()) {
                return $promocode;
            }
        }

        if ($this->attributes['id'] === $promocode->promotional->promoteable_id) {
            return $promocode;
        }

        return false;
    }

    /**
     * Apply the promo code to current model.
     * 
     * @param  String $code
     * @param  Function $callback
     * @return Mixed
     */
    public function applyCode($code, $callback = null)
    {
        if ($promocode = $this->checkCode($code)) {

            // callback function with reward value
            if (is_callable($callback)) {
                $callback($promocode->reward ?: true);
            }

            return $promocode->reward ?: true;

        }

        // callback function with false value
        if (is_callable($callback)) {
            $callback(false);
        }

        return false;
    }

    public function promotionals()
    {
        return $this->morphMany(Promotional::class, 'promoteable');
    }
}