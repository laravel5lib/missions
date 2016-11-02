<?php

namespace App\Http\Transformers\v1;
use League\Fractal\TransformerAbstract;
use Silber\Bouncer\Database\Role;

class RoleTransformer extends TransformerAbstract
{
    public function transform(Role $role)
    {
        return [
            'id'   => $role->id,
            'name' => $role->name,
        ];
    }
}