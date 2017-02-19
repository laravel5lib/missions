<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\v1\QuestionnaireRequest;
use App\Http\Transformers\v1\QuestionnaireTransformer;
use App\Models\v1\Questionnaire;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class QuestionnairesController extends Controller
{

    /**
     * @var Questionnaire
     */
    private $questionnaire;

    /**
     * QuestionnairesController constructor.
     * @param Questionnaire $questionnaire
     */
    public function __construct(Questionnaire $questionnaire)
    {
        $this->questionnaire = $questionnaire;
    }

    /**
     * Get all questionnaires.
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request)
    {
        $questionnaires = $this->questionnaire
                               ->filter($request->all())
                               ->paginate($request->get('per_page', 10));

        return $this->response->paginator($questionnaires, new QuestionnaireTransformer);
    }

    /**
     * Get one questionnaire.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($id)
    {
        $questionnaire = $this->questionnaire->findOrFail($id);

        return $this->response->item($questionnaire, new QuestionnaireTransformer);
    }

    /**
     * Create a new Questionnaire.
     *
     * @param QuestionnaireRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(QuestionnaireRequest $request)
    {
        $questionnaire = $this->questionnaire->create([
            'type' => $request->get('type'),
            'content' => $request->get('content'),
            'reservation_id' => $request->get('reservation_id')
        ]);

        return $this->response->item($questionnaire, new QuestionnaireTransformer);
    }

    /**
     * Delete a questionnaire.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($id)
    {
        $questionnaire = $this->questionnaire->findOrFail($id);

        $questionnaire->delete();

        return $this->response->noContent();
    }
}
