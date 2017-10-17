<?php

namespace App\Http\Requests\v1;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Request;

class TeamMemberRequest extends FormRequest
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
            'team_id'        => 'required|exists:teams,id',
            'reservation_id' => 'required|exists:reservations,id',
            'role_id'        => 'required|exists:roles,id',
            'group'          => 'required|string',
            'leader'         => 'boolean',
            'forms'          => 'array'
        ];
    }
}
