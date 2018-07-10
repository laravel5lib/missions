<?php

namespace App\Http\Requests;

use Dingo\Api\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequirementRequest extends FormRequest
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
        return [
            'requester_type' => 'required|string|in:trips,reservations,campaigns',
            'requester_id' => 'required|string',
            'name' => [
                'sometimes',
                'required',
                'string',
                Rule::unique('requirements')->where(function ($query) {
                        return $query->where('requester_type', $this->requester_type)
                                     ->where('requester_id', $this->requester_id);
                    })
                    ->ignore($this->route('requirement'))
            ],
            'document_type' => 'sometimes|required|string',
            'short_desc' => 'nullable|string|max:225',
            'due_at' => 'sometimes|required|date',
            'grace_period' => 'nullable|numeric'
        ];
    }
}
