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
        return [
            'card_id' => 'required_without:number',
            'number' => 'required_without:card_id|string',
            'exp_month' => 'required_with:number|digits:2',
            'exp_year' => 'required_with:number|digits:4',
            'cvc' => 'required_with:number|digits_between:3,4',
            'customer_id' => 'string'
        ];
    }
}
