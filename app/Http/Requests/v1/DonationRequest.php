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
            'donor'                       => 'array',
            'donor.*.name'                => 'required|string',
            'donor.*.company'             => 'string',
            'donor.*.email'               => 'email',
            'donor.*.phone'               => 'string',
            'donor.*.zip'                 => 'required|string',
            'donor.*.country_code'        => 'required|in:' . Country::codes(),
            'donor.*.account_id'          => 'string',
            'donor.*.account_type'        => 'in:users,groups',
            'amount'                      => 'required|numeric',
            'currency'                    => 'required|string|min:3|max:3',
            'description'                 => 'required|string|max:120',
            'comment'                     => 'string|max:120',
            'donor_id'                    => 'required|string|exists:donors,id',
            'fund_id'                     => 'required|string|exists:funds,id',
            'payment'                     => 'array',
            'payment.*.type'              => 'required|string',
            'payment.*.brand'             => 'required_if:payment.*.type,card|string',
            'payment.*.last_four'         => 'required_if:payment.*.type,card|string|min:4|max:4',
            'payment.*.cardholder'        => 'required_if:payment.*.type,card|string',
            'customer_id'                 => 'string',
            'charge_id'                   => 'required_if:payment.*.type,card|required_without:card',
            'card'                        => 'required_if:payment.*.type,card|required_without:charge_id|array',
            'card.*.number'               => 'required_with:card|string',
            'card.*.exp_month'            => 'required_with:card|digits:2',
            'card.*.exp_year'             => 'required_with:card|digits:4',
            'card.*.cvc'                  => 'required_with:card|digits_between:3,4'
        ];
    }
}
