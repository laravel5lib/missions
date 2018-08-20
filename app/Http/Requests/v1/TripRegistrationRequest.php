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
        if (!$this->input('donor')) return $this->adminRules();

        return [
            'given_names'        => 'required|max:100',
            'surname'            => 'required|max:60',
            'gender'             => 'required|in:male,female,Male,Female',
            'status'             => 'required|string',
            'shirt_size'         => 'required|in:' . $this->getShirtSizes(),
            'birthday'           => 'required|date',
            'user_id'            => 'required|exists:users,id',
            'address'            => 'required|string',
            'zip'                => 'required|string',
            'city'               => 'required|string',
            'country_code'       => 'required',
            'email'              => 'required|email',
            'phone_one'          => 'required_without:phone_two',
            'phone_two'          => 'required_without:phone_one',
            'donor'              => 'required_without:donor_id|array',
            'donor.first_name'   => 'required|string',
            'donor.last_name'    => 'required|string',
            'donor.company'      => 'string',
            'donor.email'        => 'required|email',
            'donor.phone'        => 'string',
            'donor.zip'          => 'required|string',
            'donor.country_code' => 'required|in:' . Country::codes(),
            'donor.account_id'   => 'string|exists:users,id',
            'donor.account_type' => 'in:users',
            'amount'             => 'required_unless:amount,0',
            'token'              => 'required_unless:amount,0|string',
            'description'        => 'required_unless:amount,0|string',
            'currency'           => 'required_unless:amount,0|string',
            'payment'            => 'required_unless:amount,0|array',
            'payment.type'       => 'required_unless:amount,0|in:cash,check,card',
            'payment.number'     => 'required_if:payment.type,check|string'
        ];
    }

    private function adminRules()
    {
        return [
            'given_names'        => 'required|max:100',
            'surname'            => 'required|max:60',
            'gender'             => 'required|in:male,female,Male,Female',
            'status'             => 'required|string',
            'shirt_size'         => 'required|in:' . $this->getShirtSizes(),
            'birthday'           => 'required|date',
            'user_id'            => 'required|exists:users,id',
            'address'            => 'required|string',
            'zip'                => 'required|string',
            'city'               => 'required|string',
            'country_code'       => 'required',
            'email'              => 'required|email',
            'phone_one'          => 'required_without:phone_two',
            'phone_two'          => 'required_without:phone_one'
        ];
    }

    private function getShirtSizes()
    {
        return ShirtSize::sizes();
    }
}
