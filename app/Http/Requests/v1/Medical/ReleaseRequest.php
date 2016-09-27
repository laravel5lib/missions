<?php

namespace App\Http\Requests\v1\Medical;

use Dingo\Api\Http\FormRequest;

class ReleaseRequest extends FormRequest
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
            'user_id'                        => 'required|exists:users,id',
            'name'                           => 'required|string',
            'ins_provider'                   => 'required_with:ins_provider|string',
            'ins_policy_no'                  => 'required_with:ins_policy_no|string',
            'conditions'                     => 'array',
            'conditions.*.name'              => 'required|string',
            'conditions.*.medication'        => 'required|boolean',
            'conditions.*.diagnosed'         => 'required|boolean',
            'allergies'                      => 'array',
            'allergies.*.name'               => 'required|string',
            'allergies.*.medication'         => 'required|boolean',
            'allergies.*.diagnosed'          => 'required|boolean',
            'is_risk'                        => 'boolean',
            'emergency_contact'              => 'required|array',
            'emergency_contact.name'         => 'required|string',
            'emergency_contact.email'        => 'required|email',
            'emergency_contact.phone'        => 'required|string',
            'emergency_contact.relationship' => 'required|in:friend,spouse,family,guardian,other'
        ];
    }
}
