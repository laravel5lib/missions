<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\v1\Requirement;
use App\Http\Controllers\Controller;
use App\Models\v1\RequirementCondition;
use App\Http\Requests\v1\RequirementConditionRequest;
use App\Http\Transformers\v1\RequirementConditionTransformer;

class RequirementConditionsController extends Controller
{
    private $condition;

    function __construct(Requirement $requirement, RequirementCondition $condition)
    {
        $this->requirement = $requirement;
        $this->condition = $condition;
    }

    public function index($requirementId, Request $request)
    {
        $conditions = $this->requirement->findOrFail($requirementId)->conditions()->get();

        return $this->response->collection($conditions, new RequirementConditionTransformer);
    }

    public function store($requirementId, RequirementConditionRequest $request)
    {
        $data = [
            'type' => trim($request->get('type')),
            'operator' => trim($request->get('operator')),
            'applies_to' => array_filter($request->get('applies_to'))
        ];

        $condition = $this->requirement
             ->findOrFail($requirementId)
             ->conditions()
             ->create($data);

        return $this->response->item($condition, new RequirementConditionTransformer);
    }

    public function update($requirementId, $id, RequirementConditionRequest $request)
    {   
        $condition = $this->condition
                          ->where('requirement_id', $requirementId)
                          ->findOrFail($id);

        $data = [
            'type' => trim($request->get('type', $condition->type)),
            'operator' => trim($request->get('operator', $condition->operator)),
            'applies_to' => array_filter($request->get('applies_to', $condition->applies_to))
        ];

        $condition = $this->requirement
             ->findOrFail($requirementId)
             ->conditions()
             ->create($data);

        return $this->response->item($condition, new RequirementConditionTransformer);
    }

    public function destroy($id)
    {
        //
    }
}
