<?php

namespace App\Http\Requests\v1;

use Dingo\Api\Http\FormRequest;

class PriceRequest extends FormRequest
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
            return $this->updatePriceRules();
        }

        return $this->newPriceRules();
    }

    private function newPriceRules()
    {
        return [
            'price_id'             => 'required_without:cost_id|exists:prices,uuid',
            'cost_id'              => 'required_without:price_id|exists:costs,id',
            'active_at'            => 'nullable|date',
            'amount'               => 'required_without:price_id|numeric'
        ];
    }

    private function updatePriceRules()
    {
        return [
            'price_id'             => 'sometimes|required_without:cost_id|exists:prices,uuid',
            'cost_id'              => 'sometimes|required_without:price_id|exists:costs,id',
            'active_at'            => 'nullable|date',
            'amount'               => 'sometimes|required_without:price_id|numeric'
        ];
    }

    public function messages()
    {
        return [
            'price_id.required_without' => 'Please provide a price ID.',
            'price_id.exists' => 'Price ID is not valid.',
            'cost_id.required_without' => 'Please provide a cost ID.',
            'cost_id.exists' => 'Cost ID is not valid.',
            'active_at.date' => 'Is not a valid date.',
            'amount.required_without' => 'Please enter an amount.',
            'amount.numeric' => 'Amount is not a valid format.'
        ];
    }
}
