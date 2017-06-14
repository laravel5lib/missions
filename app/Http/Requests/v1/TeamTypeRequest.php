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
            'rules.*' => 'integer',
            'campaign_id' => 'required|exists:campaigns,id'
        ];

        if ($this->isMethod('put')) {
            $rules = [
                'name' => 'sometimes|required|string|unique:team_types,name',
                'rules' => 'array',
                'rules.*' => 'integer',
                'campaign_id' => 'sometimes|required|exists:campaigns,id'
            ];
        }

        return $rules;
    }
}
