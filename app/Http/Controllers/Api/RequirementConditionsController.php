<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class RequirementConditionsController extends Controller
{
    private $condition;

    function __construct(RequirementCondition $condition)
    {
        $this->condition = $condition;
    }

    public function index($requirementId)
    {
        //
    }
}
