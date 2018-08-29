<?php

namespace App\Http\Requests\v1;

use Dingo\Api\Http\FormRequest;

class UpdatePassportRequest extends FormRequest
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
            'number' => 'sometimes|required|string',
            'expires_at' => 'sometimes|required|date|after:now',
            'birth_country' => 'sometimes|required|string',
            'citizenship' => 'sometimes|required|string',
            'upload_id' => 'nullable|string',
            'user_id' => 'sometimes|required|exists:users,id'
        ];
    }
}
