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
        return $this->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'region_id'                => 'required|exists:regions,id',
            'call_sign'                => 'required',
            'published_at'             => 'date',
            'members'                  => 'array',
            'members.*.team_id'        => 'required|exists:teams,id',
            'members.*.reservation_id' => 'required|exists:reservations,id',
            'members.*.role_id'        => 'required|exists:roles,id',
            'members.*.group'          => 'required|string',
            'members.*.leader'         => 'boolean',
            'members.*.forms'          => 'array',
            'translators'              => 'array',
            'translators.*.id'         => 'required|exists:translators,id',
            'translators.*.forms'      => 'array',
            'translators.*.forms.*'    => 'in:decision,site,medical'
        ];
    }
}
