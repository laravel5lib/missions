<?php

namespace App\Http\Controllers\api;

use App\Http\Requests\v1\DeadlineRequest;
use App\Http\Transformers\v1\DeadlineTransformer;
use App\Models\v1\Deadline;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DeadlinesController extends Controller
{

    /**
     * @var Deadline
     */
    private $deadline;

    /**
     * DeadlinesController constructor.
     * @param Deadline $deadline
     */
    public function __construct(Deadline $deadline)
    {
        $this->deadline = $deadline;
    }

    /**
     * Get all deadlines.
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request)
    {
        $deadlines = $this->deadline
                          ->filter($request->all())
                          ->paginate($request->get('per_page', 10));

        return $this->response->paginator($deadlines, new DeadlineTransformer);
    }

    /**
     * Get one deadline.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($id)
    {
        $deadline = $this->deadline->findOrFail($id);

        return $this->response->item($deadline, new DeadlineTransformer);
    }

    /**
     * Create a new deadline.
     *
     * @param DeadlineRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(DeadlineRequest $request)
    {
        $deadline = $this->deadline->create([
            'deadline_assignable_id'   => $request->get('deadline_assignable_id'),
            'deadline_assignable_type' => $request->get('deadline_assignable_type'),
            'name'                     => $request->get('name'),
            'date'                     => $request->get('date'),
            'grace_period'             => $request->get('grace_period', 0),
            'enforced'                 => $request->get('enforced', false)
        ]);

        return $this->response->item($deadline, new DeadlineTransformer);
    }

    /**
     * Update a deadline.
     *
     * @param DeadlineRequest $request
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function update(DeadlineRequest $request, $id)
    {
        $deadline = $this->deadline->findOrFail($id);

        $deadline->update([
            'deadline_assignable_id'   => $request->get('deadline_assignable_id', $deadline->deadline_assignable_id),
            'deadline_assignable_type' => $request->get('deadline_assignable_type', $deadline->deadline_assignable_type),
            'name'                     => $request->get('name'),
            'date'                     => $request->get('date'),
            'grace_period'             => $request->get('grace_period', $deadline->grace_period),
            'enforced'                 => $request->get('enforced', $deadline->enforced)
        ]);

        return $this->response->item($deadline, new DeadlineTransformer);
    }

    /**
     * Delete a deadline.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($id)
    {
        $deadline = $this->deadline->findOrFail($id);

        $deadline->delete();

        return $this->response->noContent();
    }

}
