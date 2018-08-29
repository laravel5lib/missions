<?php

namespace App\Http\Requests;

use Dingo\Api\Http\FormRequest;

class UpdateVisaRequest extends FormRequest
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
            'given_names' => 'sometimes|required|string',
            'surname' => 'sometimes|required|string',
            'number' => 'sometimes|required|string|min:6',
            'country_code' => 'sometimes|required|country',
            'issued_at' => 'sometimes|required|date|before:now',
            'expires_at' => 'sometimes|required|date|after:now',
            'user_id' => 'sometimes|required|exists:users,id'
        ];
    }
}
