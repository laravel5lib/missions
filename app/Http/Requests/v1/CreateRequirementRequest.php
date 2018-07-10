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
        return [
            'requester_type' => 'required|string|in:trips,reservations,campaigns',
            'requester_id' => 'required|string',
            'name' => [
                'required',
                'string',
                Rule::unique('requirements')->where(function ($query) {
                        return $query->where('requester_type', $this->requester_type)
                                     ->where('requester_id', $this->requester_id);
                    })
            ],
            'document_type' => 'required|string',
            'short_desc' => 'nullable|string|max:225',
            'due_at' => 'required|date',
            'grace_period' => 'nullable|numeric'
        ];
    }
}
