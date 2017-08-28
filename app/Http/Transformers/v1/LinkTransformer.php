<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\Link;
use League\Fractal\TransformerAbstract;

class LinkTransformer extends TransformerAbstract
{
    /**
     * Turn this item object into a generic array
     *
     * @param Link $link
     * @return array
     */
    public function transform(Link $link)
    {
        return [
            'id'         => $link->id,
            'name'       => $link->name,
            'url'        => $link->url,
            'created_at' => $link->created_at->toDateTimeString(),
            'updated_at' => $link->updated_at->toDateTimeString(),
        ];
    }
}
