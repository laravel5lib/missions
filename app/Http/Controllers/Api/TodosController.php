<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\v1\TodoRequest;
use App\Http\Transformers\v1\TodoTransformer;
use App\Models\v1\Todo;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class TodosController extends Controller
{

    /**
     * @var Todo
     */
    private $todo;

    /**
     * TodosController constructor.
     * @param Todo $todo
     */
    public function __construct(Todo $todo)
    {
        $this->todo = $todo;
    }

    /**
     * Get all todos.
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request)
    {
        $todos = $this->todo
                    ->filter($request->all())
                    ->paginate($request->get('per_page', 10));

        return $this->response->paginator($todos, new TodoTransformer);
    }

    /**
     * Get a todo by it's id.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($id)
    {
        $todo = $this->todo->findOrFail($id);

        return $this->response->item($todo, new TodoTransformer);
    }

    /**
     * Create a new todo.
     *
     * @param TodoRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(TodoRequest $request)
    {
        $todo = $this->todo->create($request->all());

        return $this->response->item($todo, new TodoTransformer);
    }

    /**
     * Update a todo.
     *
     * @param TodoRequest $request
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function update(TodoRequest $request, $id)
    {
        $todo = $this->todo->findOrFail($id);

        if ($request->has('complete')) {
            $this->getCompletionStatus($request);
        }

        $todo->update($request->all());

        return $this->response->item($todo, new TodoTransformer);
    }

    /**
     * Delete a todo.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($id)
    {
        $todo = $this->todo->findOrFail($id);

        $todo->delete();

        return $this->response->noContent();
    }

    /**
     * Figure out completion status and merge with request.
     *
     * @param TodoRequest $request
     */
    private function getCompletionStatus(TodoRequest $request)
    {
        if ($request->get('complete')) {
            $request->merge(['completed_at' => Carbon::now()]);
        } else {
            $request->merge(['completed_at' => null, 'user_id' => null]);
        }
    }
}
