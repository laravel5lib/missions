<?php

namespace App\Http\Requests\v1;

use Dingo\Api\Http\FormRequest;

class TeamRequest extends FormRequest
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
            'callsign' => 'required|string',
            'locked' => 'boolean',
            'associations' => 'array',
            'associations.*.id' => 'required|string',
            'associations.*.type' => 'required|in:campaigns,groups'
        ];

        if ($this->isMethod('put')) {
            $rules['callsign'] = 'sometimes|required|string';
        }

        return $rules;
    }
}
