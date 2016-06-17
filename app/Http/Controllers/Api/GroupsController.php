<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\v1\Group;
use Dingo\Api\Contract\Http\Request;
use App\Http\Requests\v1\GroupRequest;
use App\Http\Transformers\v1\GroupTransformer;

class GroupsController extends Controller
{

    /**
     * @var Group
     */
    private $group;

    /**
     * Instantiate a new GroupsController instance.
     * @param Group $group
     */
    public function __construct(Group $group)
    {
        // $this->middleware('api.auth', ['except' => ['index','show']]);
        // $this->middleware('jwt.refresh', ['except' => ['index','show']]);
        $this->group = $group;
    }

    /**
     * Get all groups.
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request)
    {
        $groups = $this->group->filter($request->all())
                    ->paginate($request->get('per_page', 25));

        return $this->response->paginator($groups, new GroupTransformer);
    }

    /**
     * Show the specified group.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($id)
    {
        $group = $this->group->findOrFail($id);

        return $this->response->item($group, new GroupTransformer);
    }

    /**
     * Store a group.
     *
     * @param GroupRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(GroupRequest $request)
    {
        $group = $this->group->create($request->all());

        if ($request->has('managers'))
            $group->managers()->create($request->get('managers'));

        return $this->response->item($group, new GroupTransformer);
    }

    /**
     * Update the specified group.
     *
     * @param GroupRequest $request
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function update(GroupRequest $request, $id)
    {
        $group = $this->group->findOrFail($id);

        $group->update($request->all());

        return $this->response->item($group, new GroupTransformer);
    }

    /**
     * Delete the specified group.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($id)
    {
        $group = $this->group->findOrFail($id);

        $group->delete();

        return $this->response->noContent();
    }
}
