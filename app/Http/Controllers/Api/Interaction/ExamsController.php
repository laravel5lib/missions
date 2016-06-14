<?php

namespace App\Http\Controllers\v1\Interaction;

use App\Http\Controllers\v1\Controller;
use App\Http\Requests\v1\Interaction\ExamRequest;
use App\Http\Transformers\v1\Interaction\ExamTransformer;
use App\Models\v1\Interaction\Exam;
use Illuminate\Http\Request;

use App\Http\Requests;

class ExamsController extends Controller
{

    /**
     * @var Exam
     */
    private $exam;

    /**
     * ContactsController constructor.
     * @param Exam $exam
     */
    public function __construct(Exam $exam)
    {
        $this->exam = $exam;

        $this->middleware('api.auth');
        $this->middleware('jwt.refresh');
    }

    public function index(Request $request)
    {
        $exams = $this->exam
                      ->filter($request->all())
                      ->paginate($request->get('per_page', 10));

        return $this->response->paginator($exams, new ExamTransformer);
    }

    public function store(ExamRequest $request)
    {
        $exam = $this->exam->create($request->all());

        $location = url('/interactions/exams/' . $exam->id);

        return $this->response->created($location);
    }

    public function show($id)
    {
        $exam = $this->exam->findOrFail($id);

        return $this->response->item($exam, new ExamTransformer);
    }

    public function update(ExamRequest $request, $id)
    {
        $exam = $this->exam->findOrFail($id);

        $exam->update($request->all());

        return $this->response->item($exam, new ExamTransformer);
    }

    public function destroy($id)
    {
        $exam = $this->exam->findOrFail($id);

        $exam->delete();

        return $this->response->noContent();
    }
}
