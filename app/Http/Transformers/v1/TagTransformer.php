<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\Tag;
use League\Fractal\TransformerAbstract;

class TagTransformer extends TransformerAbstract {

    /**
     * Turn this item object into a generic array
     *
     * @param Tag $tag
     * @return array
     */
    public function transform(Tag $tag)
    {
        return [
            'name' => $tag->name,
            'color' => $tag->color
        ];
    }
}