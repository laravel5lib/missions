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
            'description'        => 'nullable|string|max:120',
            'comment'            => 'nullable|string|max:120',
            'fund_id'            => 'required|string|exists:funds,id',
            'donor_id'           => 'required_without:donor|string|exists:donors,id',
            'donor'              => 'required_without:donor_id|array',
            'donor.first_name'   => 'required_with:donor|string|max:100',
            'donor.last_name'    => 'required_with:donor|string|max:100',
            'donor.company'      => 'nullable|string',
            'donor.email'        => 'required_with:donor|email',
            'donor.phone'        => 'nullable|string',
            'donor.zip'          => 'required_with:donor|string',
            'donor.country_code' => 'required_with:donor|in:' . Country::codes(),
            'donor.account_id'   => 'nullable|string',
            'donor.account_type' => 'nullable|in:users,groups',
            'details'            => 'required|array',
            'details.type'       => 'required|in:cash,check,card',
            'details.number'     => 'required_if:details.type,check|string',
            'token'              => 'string'
        ];

        if ($this->isMethod('put')) {
            $rules = [
                'amount'             => 'required|numeric',
                'description'        => 'nullable|string|max:120',
                'comment'            => 'nullable|string|max:120',
                'fund_id'            => 'required|string|exists:funds,id',
                'donor_id'           => 'required|string|exists:donors,id',
                'details'            => 'required|array',
                'details.type'       => 'required|in:cash,check,card',
                'details.number'     => 'required_if:details.type,check|string'
            ];
        }

        return $rules;
    }
}
