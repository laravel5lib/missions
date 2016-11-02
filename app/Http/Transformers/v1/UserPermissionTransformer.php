<?php

namespace App\Http\Transformers\v1;
use League\Fractal\TransformerAbstract;

class UserPermissionTransformer extends TransformerAbstract
{
    public function transform($permission)
    {
        return [
            'id'           => $permission->id,
            'display_name' => ucwords(str_replace('-', ' ', $permission->slug)),
            'name'         => $permission->name,
            'slug'         => $permission->slug,
            'entity_id'    => $permission->entity_id,
            'entity_type'  => $permission->entity_type,
        ];
    }
}