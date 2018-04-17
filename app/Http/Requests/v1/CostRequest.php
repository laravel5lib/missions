<?php

namespace App\Http\Requests\v1;

use Dingo\Api\Http\FormRequest;

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
            'cost_id'              => 'required_without:name|string',
            'name'                 => 'required_without:cost_id|string|max:60',
            'description'          => 'nullable|string|max:120',
            'active_at'            => 'nullable|date',
            'amount'               => 'required_without:cost_id|numeric',
            'type'                 => 'required_without:cost_id|string|in:incremental,static,optional,conditional',
            'payments'             => 'sometimes|array'
        ];
    }

    private function updateCostRules()
    {
        return [
            'cost_id'              => 'sometimes|required_without:name|string',
            'name'                 => 'sometimes|required_without:cost_id|string|max:60',
            'description'          => 'nullable|string|max:120',
            'active_at'            => 'nullable|date',
            'amount'               => 'sometimes|required_without:cost_id|numeric',
            'type'                 => 'sometimes|required_without:cost_id|string|in:incremental,static,optional,conditional',
            'payments'             => 'sometimes|array'
        ];
    }

    public function messages()
    {
        return [
            'cost_id.required_without' => 'Please provide a cost ID.',
            'cost_id.string' => 'Cost ID is not valid.',
            'name.required_without' => 'Please provide a cost name.',
            'name.string' => 'Name is not a valid format.',
            'name.max' => 'Name can only be 60 characters or less.',
            'description.string' => 'Description is not a valid format.',
            'description.max' => 'Description can only be 120 characters or less.',
            'active_at.date' => 'Is not a valid date.',
            'amount.required_without' => 'Please enter an amount.',
            'amount.numeric' => 'Amount is not a valid format.',
            'type.required_without' => 'Please provide a cost type.',
            'type.string' => 'Cost type is not valid.',
            'type.in' => 'Cost type is not valid.',
            'payments.array' => 'Payments must be provided as an array.'
        ];
    }
}
