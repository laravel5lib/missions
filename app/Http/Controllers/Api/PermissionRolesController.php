<?php

namespace App\Http\Controllers\Api;

use App\Http\Transformers\v1\RoleTransformer;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;

class PermissionRolesController extends Controller
{

    /**
     * @var Role
     */
    private $role;

    /**
     * PermissionRolesController constructor.
     * @param Role $role
     */
    public function __construct(Role $role)
    {
        $this->role = $role;
    }

    public function index()
    {
        $roles = $this->role->with('permissions')->get();

        return $this->response->collection($roles, new RoleTransformer());
    }

    public function show($id)
    {
        $role = $this->role->findOrFail($id);

        return $this->response->item($role, new RoleTransformer());
    }
}
