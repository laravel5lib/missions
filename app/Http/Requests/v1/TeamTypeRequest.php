<?php

namespace App\Http\Requests\v1;

use Dingo\Api\Http\FormRequest;

class TeamTypeRequest extends FormRequest
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
            'name' => 'required|string|unique:team_types,name',
            'rules' => 'required|array',
            'rules.*' => 'integer'
        ];

        if ($this->isMethod('put')) {
            $rules = [
                'name' => 'sometimes|required|string|unique:team_types,name',
                'rules' => 'array',
                'rules.*' => 'integer'
            ];
        }

        return $rules;
    }
}
