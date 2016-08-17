<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\Accolade;
use League\Fractal\TransformerAbstract;

class AccoladeTransformer extends TransformerAbstract {

    /**
     * Transform the object into a basic array
     *
     * @param Accolade $accolade
     * @return array
     */
    public function transform(Accolade $accolade)
    {
        return [
            'id'           => $accolade->id,
            'name'         => $accolade->name,
            'display_name' => $accolade->display_name,
            'items'        => $accolade->items,
            'created_at'   => $accolade->created_at->toDateTimeString(),
            'updated_at'   => $accolade->updated_at->toDateTimeString(),
            'links'        => [
                [
                    'rel' => 'self',
                    'uri' => '/accolades/' . $accolade->id
                ]
            ]
        ];
    }
}