<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\v1\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UsersController extends Controller
{

    /**
     * UsersController constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function index()
    {
        $this->authorize('view', $this->user);

        return view('admin.users.index');
    }

    public function show($id)
    {
        $this->authorize('view', $this->user);

        $user = $this->api->get('users/'.$id, ['include' => '']);
        return view('admin.users.show')->with('user', $user);
    }

    public function edit($id)
    {
        $this->authorize('edit', $this->user);

        return view('admin.users.edit', compact('id'));
    }

    public function create()
    {
         $this->authorize('create', $this->user);

         return view('admin.users.create');
    }

    public function impersonate($id)
    {
        $user = User::find($id);

        Auth::user()->setImpersonating($user->id);

        return redirect()->to('/dashboard');
    }

    public function stopImpersonate()
    {
        $id = Session::get('impersonate');

        Auth::user()->stopImpersonating();

        return redirect()->to('/admin/users/'.$id);
    }
}
