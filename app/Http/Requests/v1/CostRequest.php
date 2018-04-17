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
        $rules = [
            'name'                 => 'required|string|max:60',
            'description'          => 'nullable|string|max:120',
            'active_at'            => 'nullable|date',
            'amount'               => 'required|numeric',
            'type'                 => 'required|string|in:incremental,static,optional,conditional',
        ];

        if ($this->isMethod('post')) {
            $rules['payments'] = 'sometimes|array';
        }


        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'Please provide a cost name.',
            'name.string' => 'Name is not a valid format.',
            'name.max' => 'Name can only be 60 characters or less.',
            'description.string' => 'Description is not a valid format.',
            'description.max' => 'Description can only be 120 characters or less.',
            'active_at.date' => 'Is not a valid date.',
            'amount.required' => 'Please enter an amount.',
            'amount.numeric' => 'Amount is not a valid format.',
            'type.required' => 'Please provide a cost type.',
            'type.string' => 'Cost type is not valid.',
            'type.in' => 'Cost type is not valid.',
            'payments.array' => 'Payments must be provided as an array.'
        ];
    }
}
