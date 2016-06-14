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
        return [
            'user_id'     => 'required|exists:users,id',
            'given_names' => 'required|string|max:100',
            'surname'     => 'string|max:100',
            'gender'      => 'in:male,female',
            'status'      => 'in:single,married',
            'birthday'    => 'date',
            'campaign_id' => 'required|exists:campaigns,id',
            'type'        => 'required|in:translator,coordinator,transportation,director'
        ];
    }
}
