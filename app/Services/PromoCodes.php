<?php

namespace App\Services;

use App\Models\v1\PromoCode;
use Illuminate\Database\Eloquent\Model

class PromoCodes {

    // create a new promo code
    public function create($name, $reward = 0, Model $endorser, $expires = null)
    {
        PromoCode::create([
            'name' => $name,
            'code' => $this->generate(),
            'reward' => $reward*100,
            'endorser_id' => $endorser->id,
            'endorser_type' => $endorser->type,
            'expires_at' => $expires
        ]);
    }

    // generate a code
    public function generate()
    {
        return 'foo';
    }

    // check if code is still valid
    public function check($code)
    {
        return Promocode::byCode($code)->fresh()->exists();
    }

    // apply the promo code and return the reward
    public function apply($code)
    {
        if ( ! $this->check($code)) return false;

        $promo = PromoCode::byCode($code)->first();
        return $promo->reward ?: true;
    }

}
