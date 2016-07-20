<?php

namespace App\Http\Requests\v1;

use App\Http\Requests\Request;

class AssignmentRequest extends Request
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
        $required = [
            'user_id'     => 'required|exists:users,id',
            'given_names' => 'required|string|max:100',
            'campaign_id' => 'required|exists:campaigns,id',
            'type'        => 'required|in:translator,coordinator,transportation,director'
        ];

        if ($this->isMethod('put'))
        {
            $required = [
                'user_id'     => 'sometimes|required|exists:users,id',
                'given_names' => 'sometimes|required|string|max:100',
                'campaign_id' => 'sometimes|required|exists:campaigns,id',
                'type'        => 'sometimes|required|in:translator,coordinator,transportation,director'
            ];
        }

        $optional = [
            'surname'  => 'string|max:100',
            'gender'   => 'in:male,female',
            'status'   => 'in:single,married',
            'birthday' => 'date',
            'tags'     => 'array'
        ];

        return $rules = $required + $optional;
    }
}
