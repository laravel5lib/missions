<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\Accolade;
use League\Fractal\TransformerAbstract;

class AccoladeTransformer extends TransformerAbstract
{

    /**
     * Transform the object into a basic array
     *
     * @param Accolade $accolade
     * @return array
     */
    public function transform(Accolade $accolade)
    {
        $data = [
            'name'         => $accolade->name,
            'display_name' => $accolade->display_name,
            'items'        => $accolade->items,
            'created_at'   => $accolade->created_at->toDateTimeString(),
            'updated_at'   => $accolade->updated_at->toDateTimeString(),
        ];

        if ($accolade->name == 'countries_visited') {
            $data['items'] = collect($accolade->items)->map(function ($item) {
                return ['code' => $item, 'name' => country($item)];
            })->toArray();
        }

        return $data;
    }
}
