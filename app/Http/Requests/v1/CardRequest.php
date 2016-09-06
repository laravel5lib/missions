<?php

namespace App\Http\Requests\v1;

use Dingo\Api\Http\FormRequest;

class CardRequest extends FormRequest
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
        $required = [
            'cardholder' => 'required',
            'card_id'   => 'required_without:number',
            'number'    => 'required_without:card_id|string',
            'exp_month' => 'required_with:string',
            'exp_year'  => 'required_with:number|digits:4',
            'cvc'       => 'required_with:number|digits_between:3,4',
            'amount'    => 'required',
            'currency'  => 'required',
            'description' => 'required'
        ];

        if ($this->isMethod('put'))
        {
            $required = [
                'card_id'   => 'sometimes|required_without:number',
                'number'    => 'sometimes|required_without:card_id|string',
                'exp_month' => 'sometimes|required_with:number|string',
                'exp_year'  => 'sometimes|required_with:number|digits:4',
                'cvc'       => 'sometimes|required_with:number|digits_between:3,4',
                'amount'    => 'required',
                'currency'  => 'required'
            ];
        }

        $optional = [
            'customer_id' => 'string'
        ];

        return $rules = $required + $optional;
    }
}
