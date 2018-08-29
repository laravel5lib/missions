<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\v1\Reservation;
use App\Models\v1\MinorRelease;
use Spatie\QueryBuilder\Filter;
use App\Models\v1\Questionnaire;
use App\Http\Controllers\Controller;
use App\Models\v1\AirportPreference;
use App\Models\v1\ArrivalDesignation;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\QuestionnaireResource;
use App\Http\Requests\v1\QuestionnaireRequest;

class QuestionnairesController extends Controller
{
    public $types = [
        'questionnaires' => Questionnaire::class,
        'arrival-designations' => ArrivalDesignation::class,
        'airport-preferences' => AirportPreference::class,
        'minor-releases' => MinorRelease::class
    ];

    /**
     * Get all questionnaires.
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request)
    {
        $questionnaires = QueryBuilder::for($this->types[$request->segment(2)])
            ->allowedFilters([
                Filter::exact('reservation_id'),
                Filter::scope('managed_by')
            ])
            ->paginate($request->get('per_page', 25));

        return QuestionnaireResource::collection($questionnaires);
    }

    /**
     * Get one questionnaire.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($id)
    {
        $questionnaire = (new $this->types[$request->segment(2)])->findOrFail($id);

        return new QuestionnaireResource($questionnaire);
    }

    /**
     * Create a new Questionnaire.
     *
     * @param QuestionnaireRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(QuestionnaireRequest $request)
    {
        $questionnaire = (new $this->types[$request->segment(2)])->create($request->all());

        // change requirement status
        Reservation::findOrFail($request->input('reservation_id'))
            ->changeRequirementStatus('reviewing', $questionnaire->getMorphClass());

        return response()->json(['message' => 'New questionnaire created.'], 201);
    }

    /**
     * Delete a questionnaire.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($id)
    {
        $questionnaire = (new $this->types[request()->segment(2)])->findOrFail($id);

        // change requirement status
        Reservation::findOrFail($questionnaire->reservation_id)
            ->changeRequirementStatus('incomplete', $questionnaire->getMorphClass());

        $questionnaire->delete();

        return response()->json([], 204);
    }
}
