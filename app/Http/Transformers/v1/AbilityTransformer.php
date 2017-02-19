<?php

namespace App\Http\Transformers\v1;
use League\Fractal\TransformerAbstract;

class AbilityTransformer extends TransformerAbstract
{
    public function transform($ability)
    {
        return [
            'id'           => $ability->id,
            'display_name' => ucwords(str_replace('-', ' ', $ability->slug)),
            'name'         => $ability->name,
            'slug'         => $ability->slug,
            'entity_id'    => $ability->entity_id,
            'entity_type'  => $ability->entity_type,
        ];
    }
}