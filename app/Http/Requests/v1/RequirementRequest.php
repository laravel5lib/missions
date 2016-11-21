<?php

namespace App\Http\Requests\v1;

use Dingo\Api\Http\FormRequest;

class RequirementRequest extends FormRequest
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
            'requester_type' => 'required|string|in:trip,reservation',
            'requester_id' => 'required|string',
            'name' => 'required|string',
            'document_type' => 'required|in:passports,visas,medical_releases,essays,arrival_designation',
            'short_desc' => 'string|max:120',
            'due_at' => 'required|date',
            'grace_period' => 'numeric'
        ];

        if ($this->isMethod('put')) {
            $rules['requester_type'] = 'sometimes|required|string|in:trip,reservation';
            $rules['requester_id'] = 'sometimes|required|string';
        }

        return $rules;
    }
}
