<?php

namespace App\Http\Requests\v1;

use App\Http\Requests\Request;

class MediaCredentialRequest extends Request
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
            'holder_id' => 'required|string',
            'holder_type' => 'required|in:users,reservations',
            'applicant_name' => 'required|string|max:255',
            'content' => 'required|array',
            'expired_at' => 'date'
        ];

        if ($this->isMethod('put')) {
            $rules = [
                'holder_id' => 'sometimes|required|string',
                'holder_type' => 'sometimes|required|in:users,reservations',
                'applicant_name' => 'sometimes|required|string|max:255',
                'content' => 'sometimes|required|array',
                'expired_at' => 'date'
            ];
        }

        return $rules;
    }
}
