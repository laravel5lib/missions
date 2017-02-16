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
        $rules = [
            'anonymous'          => 'boolean',
            'amount'             => 'required|numeric',
            'description'        => 'string|max:120',
            'comment'            => 'string|max:120',
            'fund_id'            => 'required|string|exists:funds,id',
            'donor_id'           => 'required_without:donor|string|exists:donors,id',
            'donor'              => 'required_without:donor_id|array',
            'donor.name'         => 'required_with:donor|string',
            'donor.company'      => 'string',
            'donor.email'        => 'required_with:donor|email',
            'donor.phone'        => 'string',
            'donor.zip'          => 'required_with:donor|string',
            'donor.country_code' => 'required_with:donor|in:' . Country::codes(),
            'donor.account_id'   => 'string',
            'donor.account_type' => 'in:users,groups',
            'details'            => 'required|array',
            'details.type'       => 'required|in:cash,check,card',
            'details.number'     => 'required_if:details.type,check|string',
            'token'              => 'string',
            'card'               => 'array',
            'card.card_id'       => 'string',
            'card.cardholder'    => 'required_with:card|string',
            'card.number'        => 'required_with:card|string',
            'card.exp_month'     => 'required_with:card|string',
            'card.exp_year'      => 'required_with:card|digits:4',
            'card.cvc'           => 'required_with:card|digits_between:3,4',
        ];

        if ($this->isMethod('put')) {
            $rules = [
                'amount'             => 'required|numeric',
                'description'        => 'string|max:120',
                'comment'            => 'string|max:120',
                'fund_id'            => 'required|string|exists:funds,id',
                'donor_id'           => 'required|string|exists:donors,id',
                'details'            => 'required|array',
                'details.type'       => 'required|in:cash,check,card',
                'details.number'     => 'required_if:details.type,check|string',
                'card'               => 'array',
                'card.card_id'       => 'string',
                'card.cardholder'    => 'required_with:card|string',
            ];
        }

        return $rules;
    }
}
