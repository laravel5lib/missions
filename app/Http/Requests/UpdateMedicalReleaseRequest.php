<?php

namespace App\Http\Requests;

use Dingo\Api\Http\FormRequest;

class UpdateMedicalReleaseRequest extends FormRequest
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
            'user_id'                        => 'sometimes|required|exists:users,id',
            'name'                           => 'sometimes|required|string',
            'ins_provider'                   => 'nullable|required_with:ins_policy_no|string',
            'ins_policy_no'                  => 'nullable|required_with:ins_provider|string',
            'takes_medication'               => 'sometimes|required|boolean',
            'conditions'                     => 'array',
            'conditions.*.name'              => 'sometimes|required|string',
            'allergies'                      => 'array',
            'allergies.*.name'               => 'sometimes|required|string',
            'emergency_contact'              => 'sometimes|required|array',
            'emergency_contact.name'         => 'sometimes|required|string',
            'emergency_contact.email'        => 'sometimes|required|email',
            'emergency_contact.phone'        => 'sometimes|required|string',
            'emergency_contact.relationship' => 'sometimes|required|in:friend,spouse,family,guardian,other',
            'upload_ids'                     => 'array',
            'upload_ids.*'                   => 'sometimes|required|exists:uploads,id',
            'pregnant'                       => 'boolean',
            'height'                         => 'sometimes|required|numeric',
            'weight'                         => 'sometimes|required|numeric'
        ];
    }
}
