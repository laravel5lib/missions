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
            'name' => [
                'sometimes',
                'required',
                'string',
                Rule::unique('requirements')->where(function ($query) {
                        return $query->where('requester_type', $this->route('requireableType'))
                                     ->where('requester_id', $this->route('requireableId'));
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
