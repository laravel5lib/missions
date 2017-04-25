<?php

namespace App\Http\Requests\v1;

use Dingo\Api\Http\FormRequest;

class SquadMemberRequest extends FormRequest
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
            'members' => 'array',
            'members.*.id' => 'required_with:members|string|exists:reservations,id|unique:team_members,reservation_id,NULL,reservation_id,team_squad_id,' . $this->route('squads'),
            'members.*.leader' => 'boolean',
            'id' => 'required_without:members|string|exists:reservations,id|unique:team_members,reservation_id,NULL,reservation_id,team_squad_id,' . $this->route('squads'),
            'leader' => 'boolean'
        ];

        if ($this->isMethod('put')) {
            $rules = [
                'id' => 'sometimes|required_without:members|string|exists:reservations,id|unique:team_members,reservation_id,NULL,reservation_id,team_squad_id,' . $this->route('squads'),
                'leader' => 'required|boolean'
            ];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'members.*.id.unique' => 'Attempting to add a duplicate member.',
            'id.unique' => 'Attempting to add a duplicate member.'
        ];
    }
}
