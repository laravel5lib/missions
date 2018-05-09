<?php

namespace App\Http\Requests\v1;

use Dingo\Api\Http\FormRequest;
use Illuminate\Validation\Rule;

class CostRequest extends FormRequest
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
        if ($this->isMethod('put')) {
            return $this->updateCostRules();
        }

        return $this->newCostRules();
    }

    private function newCostRules()
    {
        return [
            'name' => [
                'required', 'string', 'max:60',
                Rule::unique('costs')
                    ->where('cost_assignable_type', $this->getCostAssignableType())
                    ->where('cost_assignable_id', $this->getCostAssignableId())
            ],
            'description' => 'required|string|max:120',
            'type' => 'required|in:incremental,static,optional,upfront',
        ];
    }

    private function updateCostRules()
    {
        return [
            'name' => [
                'sometimes', 'required', 'string', 'max:60',
                Rule::unique('costs')
                    ->where('cost_assignable_type', $this->getCostAssignableType())
                    ->where('cost_assignable_id', $this->getCostAssignableId())
                    ->ignore($this->route('cost'))
            ],
            'description' => 'sometimes|required|string|max:120'
        ];
    }

    private function getCostAssignableType()
    {
        return $this->input('cost_assignable_type', $this->segment(2));
    }

    private function getCostAssignableId()
    {
        return $this->input('cost_assignable_id', $this->segment(3));
    }

    public function messages()
    {
        return [
            'name.required' => 'Please provide a cost name.',
            'name.string' => 'Name is not a valid format.',
            'name.max' => 'Name can only be 60 characters or less.',
            'description.required' => 'Please provide a description.',
            'description.string' => 'Description is not a valid format.',
            'description.max' => 'Description can only be 120 characters or less.',
            'type.required' => 'Please provide a cost type.',
            'type.string' => 'Cost type is not valid.',
            'type.in' => 'Cost type is not valid.'
        ];
    }
}
