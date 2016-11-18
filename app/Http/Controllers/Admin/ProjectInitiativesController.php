<?php

namespace App\Http\Controllers\admin;

use App\Models\v1\ProjectInitiative;
use App\Http\Controllers\Controller;

class ProjectInitiativesController extends Controller
{

    /**
     * @var ProjectInitiative
     */
    private $initiative;

    /**
     * ProjectInitiativesController constructor.
     * @param ProjectInitiative $initiative
     */
    public function __construct(ProjectInitiative $initiative)
    {
        $this->initiative = $initiative;
    }

    public function show($id)
    {
        $initiative = $this->initiative->findOrFail($id);

        return view('admin.causes.initiatives.show', compact('initiative'));
    }
}
