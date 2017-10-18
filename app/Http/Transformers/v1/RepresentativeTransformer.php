<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\Representative;
use League\Fractal\TransformerAbstract;

class RepresentativeTransformer extends TransformerAbstract
{

    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $availableIncludes = [];

    /**
     * Turn this item object into a generic array
     *
     * @param TirpRep $rep
     * @return array
     */
    public function transform(Representative $rep)
    {
        return [
            'id'              => $rep->id,
            'name'            => $rep->name,
            'email'           => $rep->email,
            'phone'           => $rep->phone,
            'ext'             => $rep->ext,
            'avatar_url'      => $rep->avatar_url ?: asset('images/placeholders/user-placeholder.png'),
            'created_at'      => $rep->created_at->toDateTimeString(),
            'updated_at'      => $rep->updated_at->toDateTimeString(),
            'links'           => [
                [
                    'rel' => 'self',
                    'uri' => '/representatives/' . $rep->id,
                ]
            ],
        ];
    }
}
