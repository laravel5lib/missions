<?php

namespace App\Http\Transformers\v1;

use League\Fractal\TransformerAbstract;
use Silber\Bouncer\Database\Role;

class RoleTransformer extends TransformerAbstract
{
    /**
     * List of resources available to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'abilities'
    ];

    public function transform(Role $role)
    {
        return [
            'id'   => $role->id,
            'name' => $role->name,
        ];
    }

    public function includeAbilities(Role $role)
    {
        $abilities = $role->abilities;

        return $this->collection($abilities, new AbilityTransformer);
    }
}
