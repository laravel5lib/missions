<?php

namespace App\Http\Requests\v1;

use Dingo\Api\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateRequirementRequest extends FormRequest
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
        if ($this->has('requirement_id')) {
            return [
                'requirement_id' => 'required|string|exists:requirements,id'
            ];
        }

        return [
            'name' => [
                'required',
                'string',
                Rule::unique('requirements')->where(function ($query) {
                        return $query->where('requester_type', $this->route('requireableType'))
                                     ->where('requester_id', $this->route('requireableId'));
                    })
            ],
            'document_type' => 'required|string',
            'short_desc' => 'nullable|string|max:225',
            'due_at' => 'required|date',
            'grace_period' => 'nullable|numeric'
        ];
    }

    public function messages()
    {
        return [
            'requirement_id.required' => 'Please select a requirement.',
            'requirement_id.string' => 'Invalid requirement id.',
            'requirement_id.exists' => 'That requirement id does not exist.'
        ];
    }
}
