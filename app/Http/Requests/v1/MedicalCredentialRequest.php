<?php

namespace App\Http\Requests\v1;

use Dingo\Api\Http\FormRequest;

class MedicalCredentialRequest extends FormRequest
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
        if ($this->isMethod('post')) {
            return [
                'user_id' => 'required|exists:users,id',
                'applicant_name' => 'required|string|max:255',
                'content' => 'required|array',
                'expired_at' => 'nullable|date'
            ];
        }

        return [
            'user_id' => 'sometimes|required|exists:users,id',
            'applicant_name' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|array',
            'expired_at' => 'nullable|date'
        ];
    }
}
