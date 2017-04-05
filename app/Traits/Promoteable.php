<?php

namespace App\Traits;

use App\Facades\Promocodes;
use App\Models\v1\Promocode;

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
    public function createCode(
        $name, 
        $amount = 1, 
        $reward = null, 
        $expires = null,
    )
    {
        $records = [];

        // loop though each promocodes required
        foreach (Promocodes::output($amount) as $code) {
            $records[] = new Promocode([
                'name'   => $name,
                'code'   => $code,
                'reward' => $reward,
                'expires_at' => $expires
            ]);
        }

        // check for insertion of record
        if ($this->promocodes()->saveMany($records)) {
            return collect($records);
        }

        return collect([]);
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

        $promoter = str_singular($promocode->promoter_type);

        if (! $promoter) return $promocode;

        if (method_exists($this, $promoter)) {
            if ($this->{$promoter}()->where('id', $promocode->promoter_id)->exists()) {
                return $promocode;
            }
        }

        if ($this->attributes['id'] === $promocode->promoter_id) {
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

    /**
     * Get all of the model's promocodes.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function promocodes()
    {
        return $this->morphMany(Promocode::class, 'promoteable');
    }
}