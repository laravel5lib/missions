<?php

namespace App\Http\Transformers\v1;

use League\Fractal\TransformerAbstract;
use Spatie\Permission\Models\Role;

class RoleTransformer extends TransformerAbstract
{
    /**
     * List of resources available to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'permissions'
    ];

    public function transform(Role $role)
    {
        return [
            'id'   => $role->id,
            'name' => $role->name,
        ];
    }

    public function includePermissions(Role $role)
    {
        $permissions = $role->permissions;

        return $this->collection($permissions, new PermissionTransformer);
    }
}
