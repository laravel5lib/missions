<?php

namespace App\Http\Requests\v1;

use Dingo\Api\Http\FormRequest;

class ReferralRequest extends FormRequest
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
            'user_id' => 'required|exists:users,id',
            'referral_name' => 'required|string',
            'referral_email' => 'required|email',
            'referral_phone' => 'required|string',
            'status' => 'required|string'
        ];
    }
}
