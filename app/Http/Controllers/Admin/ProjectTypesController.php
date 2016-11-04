<?php

namespace App\Http\Controllers\admin;

use App\Models\v1\ProjectCause;
use App\Models\v1\ProjectType;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ProjectTypesController extends Controller
{

    /**
     * @var ProjectCause
     */
    private $cause;
    /**
     * @var ProjectType
     */
    private $type;

    /**
     * ProjectTypesController constructor.
     * @param ProjectCause $cause
     * @param ProjectType $type
     */
    public function __construct(ProjectCause $cause, ProjectType $type)
    {
        $this->cause = $cause;
        $this->type = $type;
    }

    public function show($causeId, $typeId)
    {
        $type = $this->type
                     ->where('project_cause_id', $causeId)
                     ->findOrFail($typeId);

        return view('admin.causes.types.show', compact('type'));
    }
}
