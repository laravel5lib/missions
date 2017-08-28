<?php

namespace App\Http\Transformers\v1;

use League\Fractal\TransformerAbstract;

class PermissionTransformer extends TransformerAbstract
{
    public function transform($permission)
    {
        return [
            'id'           => $permission->id,
            'name'         => $permission->name
        ];
    }
}
