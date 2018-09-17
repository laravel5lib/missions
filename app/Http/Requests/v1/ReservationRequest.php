<?php

namespace App\Http\Requests\v1;

use App\Utilities\v1\ShirtSize;
use Carbon\Carbon;
use Dingo\Api\Http\FormRequest;

class ReservationRequest extends FormRequest
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
            'given_names'        => 'required|max:100',
            'surname'            => 'required|max:60',
            'gender'             => 'required|in:male,female,Male,Female',
            'status'             => 'required|string',
            'shirt_size'         => 'required|string',
            'birthday'           => 'required|date',
            'user_id'            => 'required|exists:users,id',
            'trip_id'            => 'required|exists:trips,id',
            'rep_id'             => 'nullable|string|exists:representatives,id',
            'avatar_upload_id'   => 'nullable|string|exists:uploads,id',
            'passport_id'        => 'nullable|string|exists:passports,id',
            'visa_id'            => 'nullable|string|exists:visas,id',
            'medical_release_id' => 'nullable|string|exists:medical_releases,id',
            'testimony_id'       => 'nullable|string|exists:essays,id',
            'tags'               => 'array',
            'costs'              => 'array',
            'costs.*.id'         => 'required|exists:prices,uuid',
            'costs.*.locked'     => 'boolean',
            'companion_limit'    => 'numeric'
        ];

        if ($this->isMethod('put')) {
            $rules = [
                'given_names'        => 'sometimes|required|max:100',
                'surname'            => 'sometimes|required|max:60',
                'gender'             => 'sometimes|required',
                'status'             => 'sometimes|required|string',
                'shirt_size'         => 'sometimes|required|string',
                'birthday'           => 'sometimes|required|date',
                'user_id'            => 'sometimes|required|exists:users,id',
                'trip_id'            => 'sometimes|required|exists:trips,id',
                'rep_id'             => 'nullable|string|exists:representatives,id',
                'avatar_upload_id'   => 'nullable|string|exists:uploads,id',
                'passport_id'        => 'nullable|string|exists:passports,id',
                'visa_id'            => 'nullable|string|exists:visas,id',
                'medical_release_id' => 'nullable|string|exists:medical_releases,id',
                'testimony_id'       => 'nullable|string|exists:essays,id',
                'tags'               => 'array',
                'costs'              => 'array',
                'costs.*.id'         => 'required|exists:prices,uuid',
                'costs.*.locked'     => 'boolean',
                'companion_limit'    => 'numeric'
            ];
        }

        return $rules;
    }

    private function getShirtSizes()
    {
        return ShirtSize::sizes();
    }
}
