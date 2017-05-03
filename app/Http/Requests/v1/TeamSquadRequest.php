<?php

namespace App\Http\Requests\v1;

use Dingo\Api\Http\FormRequest;

class TeamSquadRequest extends FormRequest
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
            'callsign' => 'sometimes|required|string',
            'team_id'  => 'sometimes|required|string|exists:teams,id'
        ];

        if ($this->isMethod('post')) {
            $rules['callsign'] = 'required|string';
        }

        return $rules;
    }
}
