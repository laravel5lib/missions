<?php

namespace App\Http\Requests\v1\Transactions;

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
            'amount'          => 'required|integer',
            'description'     => 'required|string',
            'fund_id'         => 'required|string|exists:funds,id',
            'payment'         => 'required|array',
            'payment.type'    => 'required|in:cash,check,card',
            'payment.number'  => 'required_if:payment.type,check|string',
            'token'           => 'required_without:card|string',
            'card'            => 'required_without:token|array',
            'card.card_id'    => 'string',
            'card.cardholder' => 'required_with:card|string',
            'card.number'     => 'required_with:card|string',
            'card.exp_month'  => 'required_with:card|string',
            'card.exp_year'   => 'required_with:card|digits:4',
            'card.cvc'        => 'required_with:card|digits_between:3,4',
        ];
    }
}
