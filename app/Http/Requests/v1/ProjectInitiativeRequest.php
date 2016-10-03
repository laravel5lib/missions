<?php

namespace App\Http\Requests\v1;

use App\Http\Requests\Request;

class ProjectInitiativeRequest extends Request
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
        return [
            'name'         => 'required|max:50',
            'cause_id'     => 'required|exists:causes,id',
            'country_code' => 'required',
            'started_at'   => 'date',
            'ended_at'     => 'date',
            'active'       => 'boolean',
            'rep_id'       => 'exists:users,id'
        ];
    }
}
