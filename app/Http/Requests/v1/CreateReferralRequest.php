<?php

namespace App\Http\Requests\v1;

use Dingo\Api\Http\FormRequest;

class CreateReferralRequest extends FormRequest
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
            'applicant_name' => 'required|string',
            'type' => 'required',
            'user_id' => 'required|exists:users,id',
            'attention_to' => 'required|string',
            'recipient_email' => 'required|email',
            'referrer' => 'required|array',
            'referrer.name' => 'required|string',
            'referrer.title' => 'required|string',
            'referrer.phone' => 'required|string'
        ];
    }
}
