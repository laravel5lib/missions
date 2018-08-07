<?php

namespace App\Http\Requests\v1;

use Dingo\Api\Http\FormRequest;

class PassportRequest extends FormRequest
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
            'given_names' => 'required|string',
            'surname' => 'required|string',
            'number' => 'required|string',
            'expires_at' => 'required|date|after:now',
            'birth_country' => 'required|string',
            'citizenship' => 'required|string',
            'upload_id' => 'nullable|string',
            'user_id' => 'required|exists:users,id'
        ];

        if ($this->isMethod('put')) {
            $rules['user_id'] = 'exists:users,id';
        }

        return $rules;
    }
}
