<?php

namespace App\Http\Requests\v1;

use Carbon\Carbon;
use App\Utilities\v1\Country;
use App\Utilities\v1\ShirtSize;
use Dingo\Api\Http\FormRequest;

class TripRegistrationRequest extends FormRequest
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
            'given_names'        => 'required|max:100',
            'surname'            => 'required|max:60',
            'gender'             => 'required|in:male,female',
            'status'             => 'required|string',
            'shirt_size'         => 'required|in:' . $this->getShirtSizes(),
            'birthday'           => 'required|date|before:' . Carbon::now()->subYears(12),
            'user_id'            => 'required|exists:users,id',
            'address'            => 'required|string',
            'zip'                => 'required|string',
            'city'               => 'required|string',
            'country_code'       => 'required',
            'email'              => 'required|email',
            'phone_one'          => 'required_without:phone_two',
            'phone_two'          => 'required_without:phone_one',
            'donor'              => 'required_without:donor_id|array',
            'donor.name'         => 'required|string',
            'donor.company'      => 'string',
            'donor.email'        => 'required|email',
            'donor.phone'        => 'string',
            'donor.zip'          => 'required|string',
            'donor.country_code' => 'required|in:' . Country::codes(),
            'donor.account_id'   => 'string|exists:users,id',
            'donor.account_type' => 'in:users',
            'amount'             => 'required',
            'token'              => 'required|string',
            'description'        => 'required|string',
            'currency'           => 'required|string',
            'payment'            => 'required|array',
            'payment.type'       => 'required|in:cash,check,card',
            'payment.number'     => 'required_if:payment.type,check|string',
        ];

        return $rules;
    }

    private function getShirtSizes()
    {
        return ShirtSize::sizes();
    }
}
