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
        if ($this->isMethod('post')) {
            $rules = [
                'reservation_ids' => 'required',
                'squad_id' => 'required|exists:squads,uuid',
                'group' => 'nullable|string'
            ];
        }

        return [
            'reservation_ids' => 'required',
            'squad_id' => 'required|exists:squads,uuid',
            'group' => 'nullable|string'
        ];
    }
}
