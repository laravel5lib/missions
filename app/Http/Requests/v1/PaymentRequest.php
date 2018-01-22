<?php

namespace App\Http\Requests\v1;

use Dingo\Api\Http\FormRequest;

class PaymentRequest extends FormRequest
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
            'amount_owed' => 'required|numeric',
            'percent_owed' => 'required|numeric',
            'due_at' => 'nullable|date',
            'upfront' => 'boolean',
            'grace_period' => 'required|numeric'
        ];
    }
}
