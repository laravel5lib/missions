<?php

namespace App\Http\Requests\v1;

use App\Http\Requests\Request;
use App\Utilities\v1\Country;

class DonorRequest extends Request
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
            'name'                        => 'required|string',
            'email'                       => 'email',
            'phone'                       => 'string',
            'address_one'                 => 'string',
            'address_two'                 => 'string',
            'city'                        => 'string',
            'state'                       => 'string',
            'zip'                         => 'required|string',
            'country_code'                => 'required|in:' . Country::codes(),
            'account_holder_id'           => 'string',
            'account_holder_type'         => 'in:users,groups',
            'donation'                    => 'array',
            'donation.*.amount'           => 'required|numeric',
            'donation.*.currency'         => 'required|string|min:3|max:3',
            'donation.*.description'      => 'required|string|max:120',
            'donation.*.message'          => 'string|max:120',
            'donation.*.anonymous'        => 'boolean',
            'donation.*.designation_id'   => 'required|string',
            'donation.*.designation_type' => 'required|string|in:reservations,projects,fundraisers,trips',
            'donation.*.payment_type'     => 'required|string|in:card,check,cash,credit,refund',
            'donation.*.customer_id'      => 'string',
            'donation.*.charge_id'        => 'required_if:payment_type,card|required_without:card',
            'donation.*.card'             => 'required_if:payment_type,card|required_without:charge_id|array',
            'donation.*.card.*.number'    => 'required_with:card|string',
            'donation.*.card.*.exp_month' => 'required_with:card|digits:2',
            'donation.*.card.*.exp_year'  => 'required_with:card|digits:4',
            'donation.*.card.*.cvc'       => 'required_with:card|digits_between:3,4'
        ];
    }
}
