<?php

namespace App\Http\Controllers\Admin;

use App\Models\v1\Group;
use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Traits\SEOTools;

class GroupsController extends Controller
{
    use SEOTools;

    /**
     * @var Group
     */
    private $group;

    /**
     * GroupsController constructor.
     * @param Group $group
     */
    public function __construct(Group $group)
    {
        $this->group = $group;
    }

    /**
     * Get a list of groups.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $this->authorize('view', $this->group);

        $this->seo()->setTitle('Groups');

        return view('admin.groups.index');
    }

    /**
     * Get the specified group.
     *
     * @param $id
     * @return $this
     */
    public function show($id)
    {
        $this->authorize('view', $this->group);

        $group = $this->api->get('groups/'.$id, ['include' => 'trips']);

        $this->seo()->setTitle($group->name . ' Group');

        return view('admin.groups.show')->with('group', $group);
    }

    /**
     * Edit the specified group.
     *
     * @param $id
     * @return $this
     */
    public function edit($id)
    {
        $this->authorize('edit', $this->group);

        $this->seo()->setTitle('Edit Group');

        return view('admin.groups.edit')->with('id', $id);
    }

    /**
     * Create a new group.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $this->authorize('create', $this->group);

        $this->seo()->setTitle('Create Group');

        return view('admin.groups.create');
    }
}
