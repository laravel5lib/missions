<?php

namespace App\Http\Requests\v1;

use App\Models\v1\Cost;
use App\Models\v1\Price;
use Dingo\Api\Http\FormRequest;

class PriceRequest extends FormRequest
{
    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            if ($this->assertRequiredPayments()) {
                $validator->errors()->add('payments', 'At least one payment is required.');
            }
            
        });
    }

    /**
     * Assert if payments are required.
     *
     * @return boolean
     */
    private function assertRequiredPayments()
    {
        if ($this->filled('payments')) return false;

        if ($this->filled('cost_id')) {
            $cost = Cost::findOrFail($this->input('cost_id'));     
        }

        if ($this->filled('price_id')) {
            $cost = Price::whereUuid($this->input('price_id'))->firstOrFail()->cost;     
        }

        return ($cost && $cost->type === 'incremental');
    }

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

    /**
     * Get rules for adding a new price
     *
     * @return array
     */
    private function newPriceRules()
    {
        return [
            'price_id'             => 'required_without:cost_id|exists:prices,uuid',
            'cost_id'              => 'required_without:price_id|exists:costs,id',
            'active_at'            => 'nullable|date',
            'amount'               => 'required_without:price_id|numeric'
        ];
    }

    /**
     * Get rules for updating a price.
     *
     * @return array
     */
    private function updatePriceRules()
    {
        return [
            'price_id'             => 'sometimes|required_without:cost_id|exists:prices,uuid',
            'cost_id'              => 'sometimes|required_without:price_id|exists:costs,id',
            'active_at'            => 'nullable|date',
            'amount'               => 'sometimes|required_without:price_id|numeric'
        ];
    }

    /**
     * The error messages that apply to the request.
     *
     * @return array
     */
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
