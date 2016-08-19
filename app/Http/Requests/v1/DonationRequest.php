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
            'donor.*.email'               => 'email',
            'donor.*.phone'               => 'string',
            'donor.*.address_one'         => 'string',
            'donor.*.address_two'         => 'string',
            'donor.*.city'                => 'string',
            'donor.*.state'               => 'string',
            'donor.*.zip'                 => 'required|string',
            'donor.*.country_code'        => 'required|in:' . Country::codes(),
            'donor.*.account_holder_id'   => 'string',
            'donor.*.account_holder_type' => 'in:users,groups',
            'amount'                      => 'required|numeric',
            'currency'                    => 'required|string|min:3|max:3',
            'description'                 => 'required|string|max:120',
            'message'                     => 'string|max:120',
            'anonymous'                   => 'boolean',
            'donor_id'                    => 'required|string',
            'designation_id'              => 'required|string',
            'designation_type'            => 'required|string|in:reservations,projects,fundraisers,trips',
            'payment_type'                => 'required|string|in:card,check,cash,credit,refund',
            'customer_id'                 => 'string',
            'charge_id'                   => 'required_if:payment_type,card|required_without:card',
            'card'                        => 'required_if:payment_type,card|required_without:charge_id|array',
            'card.*.number'               => 'required_with:card|string',
            'card.*.exp_month'            => 'required_with:card|digits:2',
            'card.*.exp_year'             => 'required_with:card|digits:4',
            'card.*.cvc'                  => 'required_with:card|digits_between:3,4'
        ];
    }
}
