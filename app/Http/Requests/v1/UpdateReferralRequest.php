<?php

namespace App\Http\Requests\v1;

use Dingo\Api\Http\FormRequest;

class UpdateReferralRequest extends FormRequest
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
            'applicant_name' => 'sometimes|required|string',
            'type' => 'sometimes|required',
            'user_id' => 'sometimes|required|exists:users,id',
            'attention_to' => 'sometimes|required|string',
            'recipient_email' => 'sometimes|required|email',
            'referrer' => 'sometimes|required|array',
            'referrer.name' => 'sometimes|required|string',
            'referrer.title' => 'sometimes|required|string',
            'referrer.phone' => 'sometimes|required|string'
        ];
    }
}
