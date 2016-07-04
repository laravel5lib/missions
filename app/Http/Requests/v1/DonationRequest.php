<?php

namespace App\Http\Requests\v1;

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
            'name' => 'required|string',
            'company_name' => 'string',
            'amount' => 'required|numeric',
            'currency' => 'required|string|min:3|max:3',
            'description' => 'required|string|max:120',
            'message' => 'string|max:120',
            'anonymous' => 'boolean',
            'email' => 'email|max:60',
            'phone' => 'phone|string|max:60',
            'address_street' => 'string|max:100',
            'address_city' => 'string|max:60',
            'address_state' => 'string|max:60',
            'address_zip' => 'string|max:10',
            'address_country_code' => 'string|min:2|max:2',
            'donor_id' => 'required|string',
            'donor_type' => 'required|string|in:users,groups',
            'designation_id' => 'required|string',
            'designation_type' => 'required|string|in:reservations,projects,fundraisers,trips',
            'payment_type' => 'required|string|in:card,check,cash,credit,refund',
//            'customer' => 'array',
//            'customer.*.id' => 'required_string',
            'card' => 'required_if:payment_type,card|array',
            'card.*.number' => 'required_with:card|string',
            'card.*.exp_month' => 'required_with:card|digits:2',
            'card.*.exp_year' => 'required_with:card|digits:4',
            'card.*.cvc' => 'required_with:card|digits_between:3,4'
        ];
    }
}
