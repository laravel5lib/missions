<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\v1\GroupSubmissionRequest;
use App\Http\Transformers\v1\NoteTransformer;
use App\Models\v1\Group;
use Dingo\Api\Contract\Http\Request;
use App\Http\Requests\v1\GroupRequest;
use App\Http\Transformers\v1\GroupTransformer;
use Ramsey\Uuid\Uuid;

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
         $this->middleware('api.auth', ['only' => ['store','update','destroy']]);
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
     * Get all the group's notes.
     *
     * @param $id
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function notes($id, Request $request)
    {
        $group = $this->group->findOrFail($id);

        $notes = $group->notes()->paginate($request->get('per_page', 25));

        return $this->response->paginator($notes, new NoteTransformer);
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

        $group->syncmanagers($request->get('managers'));

        return $this->response->item($group, new GroupTransformer);
    }

    /**
     * Submit a new group for approval.
     *
     * @param GroupSubmissionRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function submit(GroupSubmissionRequest $request)
    {
        $request->merge(['status' => 'pending']);

        $group = $this->group->create($request->all());

        $group->notes()->create([
            'user_id' => Uuid::uuid4(),
            'subject' => 'New Group Submission',
            'content' => 'Contact: ' . $request->get('contact') .
                ', Position: ' . $request->get('position') .
                ', Spoken with MM Rep: ' . $request->get('spoke_to_rep') .
                ', Campaign of Interest: ' . $request->get('campaign')
        ]);

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

        $group->syncmanagers($request->get('managers'));

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
