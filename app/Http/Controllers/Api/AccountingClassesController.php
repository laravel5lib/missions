<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\v1\AccountingClass;
use App\Http\Controllers\Controller;

class AccountingClassesController extends Controller
{
    protected $class;

    function __construct(AccountingClass $class)
    {
        $this->class = $class;
    }

    public function index()
    {
        $classes = $this->class->all();

        return ['data' => $classes];
    }

    public function show($id)
    {
        $class = $this->class->findOrFail($id);

        return ['data' => $class];
    }
}
