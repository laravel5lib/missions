<?php

namespace App\Traits;

use App\Facades\Promocodes;
use App\Models\v1\Promocode;

trait Rewardable
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
        $expires = null
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
     * Apply promocode for reward recepient and get callback.
     *
     * @param $code
     * @param $callback
     *
     * @return bool|float
     */
    public function applyCode($code, $callback = null)
    {
        $promocode = Promocode::byCode($code)->fresh()->first();

        // check if exists not expired code
        if (!is_null($promocode)) {

            // check if a rewardable resource exists and if the code applies to the given rewardable
            if (!is_null($promocode->rewardable) && $promocode->rewardable->id !== $this->attributes['id']) {

                // callback function with false value
                if (is_callable($callback)) {
                    $callback(false);
                }

                return false;
            }

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
}