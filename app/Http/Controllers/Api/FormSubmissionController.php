<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Spatie\QueryBuilder\Filter;
use App\Models\v1\FormSubmission;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\FormSubmissionResource;
use App\Http\Requests\CreateFormSubmissionRequest;

class FormSubmissionController extends Controller
{
    public function index()
    {
        $forms = QueryBuilder::for(
                FormSubmission::whereType(request()->segment(2))
            )
            ->allowedFilters([
                Filter::exact('user_id')
            ])
            ->allowedIncludes(['user'])
            ->paginate(
                request()->input('per_page', 25)
            );

        return FormSubmissionResource::collection($forms);
    }

    public function store(CreateFormSubmissionRequest $request)
    {
        $data = $request->merge(['type' => $request->segment(2)])->all();

        $form = FormSubmission::create($data);

        $form->attachToReservation($request->input('reservation_id'));

        return response()->json(['message' => 'New form submission created.'], 201);
    }

    public function show()
    {
        //
    }
}
