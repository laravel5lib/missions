<?php

namespace App\Http\Controllers\Admin;

use App\Models\v1\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class UsersController extends Controller
{
    use SEOTools;

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

        $this->seo()->setTitle('Users');

        return view('admin.users.index');
    }

    public function show($id, $tab="details")
    {
        $this->authorize('view', $this->user);

        $user = User::findOrFail($id);

        $this->seo()->setTitle($user->name);

        $pageLinks = [
            'admin/users/'.$user->id => 'Overview',
        ];

        if (auth()->user()->hasAnyRole(['super_admin', 'admin'])) {
            $pageLinks['admin/users/'.$user->id.'/permissions'] = 'Permissions';
        }

        return view('admin.users.tabs.'.$tab, compact('user', 'pageLinks'));
    }

    public function edit($id)
    {
        $user = User::with('links')->findOrFail($id);

        $this->authorize('update', $user);

        $this->seo()->setTitle('Edit User');

        return view('admin.users.edit', compact('user'));
    }

    public function create()
    {
         $this->authorize('create', $this->user);

         $this->seo()->setTitle('Create User');

         return view('admin.users.create');
    }

    public function impersonate($id)
    {
        $user = User::find($id);

        return redirect()->to('/dashboard')->withCookie('impersonate', $user->id, 60, null, null, false, false);
    }

    public function stopImpersonate()
    {
        $id = Cookie::get('impersonate');

        $cookie = Cookie::forget('impersonate');

        return redirect()->to('/admin/users/'.$id)->withCookie($cookie);
    }
}
