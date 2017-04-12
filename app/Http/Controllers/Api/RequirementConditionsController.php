<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\v1\Requirement;
use App\Http\Controllers\Controller;
use App\Models\v1\RequirementCondition;
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
}
