<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EssaysController extends Controller
{
    public function create()
    {
        $this->authorize('create', Essay::class);

        return view('admin.records.essays.create');
    }

    public function show(Essay $essay)
    {
        $this->authorize('view', $essay);

        return view('admin.records.essays.show', compact('essay'));
    }

    public function edit(Essay $essay)
    {
        $this->authorize('update', $essay);

        return view('admin.records.essays.edit')->with('id', $essay->id);
    }
}
