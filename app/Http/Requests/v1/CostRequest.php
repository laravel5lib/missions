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
            'cost_assignable_id'   => 'required|string',
            'cost_assignable_type' => 'required|string|in:trips,reservations,projects',
            'name'                 => 'required|string',
            'description'          => 'string',
            'active_at'            => 'required|date',
            'amount'               => 'required|numeric',
            'type'                 => 'required|string|in:incremental,static,optional,conditional',
        ];

        if ($this->isMethod('put')) {
            $rules['cost_assignable_id'] = 'sometimes|required|string';
            $rules['cost_assignable_type'] = 'sometimes|required|string|in:trips,reservations,projects';
        }

        if ($this->isMethod('post'))
        {
            $rules['payments'] = 'required|array';
            $rules['payments.*.amount_owed'] = 'required|numeric';
            $rules['payments.*.percent_owed'] = 'required|numeric';
            $rules['payments.*.due_at'] = 'date';
            $rules['payments.*.upfront'] = 'boolean';
            $rules['payments.*.grace_period'] = 'required|numeric';
        }

        return $rules;
    }
}
