<?php

namespace App\Http\Requests\v1;

use Dingo\Api\Http\FormRequest;

class TeamableRequest extends FormRequest
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
            'ids' => 'sometimes|required|array'
        ];

        if ($this->isMethod('post')) {
            $rules = [
                'ids'   => 'required|array',
                'ids.*' => 'unique:teamables,teamable_id,NULL,teamable_id,team_id,'.$this->route('id')
            ];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'ids.*.unique' => 'Attempting to add a duplicate.',
        ];
    }
}
