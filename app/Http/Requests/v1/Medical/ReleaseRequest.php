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
            'ins_provider'                   => 'nullable|required_with:ins_provider|string',
            'ins_policy_no'                  => 'nullable|required_with:ins_policy_no|string',
            'takes_medication'               => 'required|boolean',
            'conditions'                     => 'array',
            'conditions.*.name'              => 'required|string',
            'allergies'                      => 'array',
            'allergies.*.name'               => 'required|string',
            'emergency_contact'              => 'required|array',
            'emergency_contact.name'         => 'required|string',
            'emergency_contact.email'        => 'required|email',
            'emergency_contact.phone'        => 'required|string',
            'emergency_contact.relationship' => 'required|in:friend,spouse,family,guardian,other',
            'upload_ids'                     => 'array',
            'upload_ids.*'                   => 'required|exists:uploads,id',
            'pregnant'                       => 'boolean',
            'height'                         => 'required|numeric',
            'weight'                         => 'required|numeric'
        ];
    }
}
