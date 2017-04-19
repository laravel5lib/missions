<?php

namespace App\Http\Requests\v1;

use Dingo\Api\Http\FormRequest;

class ActivityRequest extends FormRequest
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
            'name' => 'required|string|max:100',
            'description' => 'string|max:200',
            'occured_at' => 'required|date',
            'participant_id' => 'required|string',
            'participant_type' => 'required|string'
        ];

        if ($this->isMethod('put')) {
            $rules = [
                'name' => 'sometimes|required|string|max:100',
                'description' => 'string|max:200',
                'occured_at' => 'sometimes|required|date',
                'participant_id' => 'sometimes|required|string',
                'participant_type' => 'sometimes|required|string'
            ];
        }

        return $rules;
    }
}
