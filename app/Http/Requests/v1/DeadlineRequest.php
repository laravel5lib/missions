<?php

namespace App\Http\Requests\v1;

use Dingo\Api\Http\FormRequest;

class DeadlineRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'deadline_assignable_type' => 'required|in:trips,reservations,projects,project-initiatives',
            'deadline_assignable_id' => 'required|string',
            'name' => 'required|string',
            'date' => 'required|date',
            'grace_period' => 'numeric',
            'enforced' => 'boolean'
        ];

        if ($this->isMethod('put')) {
            $rules['deadline_assignable_type'] = 'sometimes|required|in:trips,reservations,projects';
            $rules['deadline_assignable_id'] = 'sometimes|required|string';
        }

        return $rules;
    }
}
