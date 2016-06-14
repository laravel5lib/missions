<?php

namespace App\Http\Controllers\v1;

use App\Http\Requests\v1\AssignmentRequest;
use App\Http\Transformers\v1\AssignmentTransformer;
use App\models\v1\Assignment;
use Illuminate\Http\Request;

use App\Http\Requests;

class AssignmentsController extends Controller
{

    /**
     * @var Assignment
     */
    private $assignment;

    /**
     * AssignmentsController constructor.
     * @param Assignment $assignment
     */
    public function __construct(Assignment $assignment)
    {
        $this->assignment = $assignment;
    }

    public function index(Request $request)
    {
        $assignments = $this->assignment
                            ->filter($request->all())
                            ->paginate($request->get('per_page', 10));

        return $this->response->paginator($assignments, new AssignmentTransformer);
    }

    public function show($id)
    {
        $assignment = $this->assignment->findOrFail($id);

        return $this->response->item($assignment, new AssignmentTransformer);
    }

    public function store(AssignmentRequest $request)
    {
        $assignment = $this->assignment->create($request->all());

        return $this->response->item($assignment, new AssignmentTransformer);
    }

    public function update(AssignmentRequest $request, $id)
    {
        $assignment = $this->assignment->findOrFail($id);

        $assignment->update($request->all());

        return $this->response->item($assignment, new AssignmentTransformer);
    }

    public function destroy($id)
    {
        $assignment = $this->assignment->findOrFail($id);

        $assignment->delete();

        return $this->response->noContent();
    }
}
