<?php

namespace App\Http\Requests\v1;

use App\Utilities\v1\Country;
use Dingo\Api\Http\FormRequest;

class DonationRequest extends FormRequest
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
            'amount'             => 'required|numeric',
            'currency'           => 'required|string|min:3|max:3',
            'description'        => 'required|string|max:120',
            'comment'            => 'string|max:120',
            'fund_id'            => 'required|string|exists:funds,id',
            'donor_id'           => 'required_without:donor|string|exists:donors,id',
            'donor'              => 'required_without:donor_id|array',
            'donor.name'         => 'required|string',
            'donor.company'      => 'string',
            'donor.email'        => 'required|email',
            'donor.phone'        => 'string',
            'donor.zip'          => 'required|string',
            'donor.country_code' => 'required|in:' . Country::codes(),
            'donor.account_id'   => 'string',
            'donor.account_type' => 'in:users,groups',
            'payment'            => 'required|array',
            'payment.type'       => 'required|in:cash,check,card',
            'payment.number'     => 'required_if:payment.type,check|string',
            'token' => 'required_without:card|string',
            'card' => 'required_without:token|array',
            'card.card_id'   => 'string',
            'card.cardholder' => 'required_with:card|string',
            'card.number'    => 'required_with:card|string',
            'card.exp_month' => 'required_with:card|string',
            'card.exp_year'  => 'required_with:card|digits:4',
            'card.cvc'       => 'required_with:card|digits_between:3,4',
        ];
    }
}
