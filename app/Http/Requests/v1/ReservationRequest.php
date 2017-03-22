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
            'birthday'           => 'required|date|before:' . Carbon::now()->subYears(12),
            'user_id'            => 'required|exists:users,id',
            'trip_id'            => 'required|exists:trips,id',
            'rep_id'             => 'string|exists:reps,id',
            'avatar_upload_id'   => 'string|exists:uploads,id',
            'passport_id'        => 'string|exists:passports,id',
            'visa_id'            => 'string|exists:visas,id',
            'medical_release_id' => 'string|exists:medical_releases,id',
            'testimony_id'       => 'string|exists:essays,id',
            'tags'               => 'array',
            'costs'              => 'array',
            'costs.*.id'         => 'required|exists:costs,id',
            'costs.*.locked'     => 'boolean',
            'companion_limit'    => 'numeric',
            'weight'             => 'required|numeric',
            'height_a'           => 'required|numeric',
            'height_b'           => 'required|numeric'
        ];

        if ($this->isMethod('put')) {
            $rules = [
                'given_names'        => 'sometimes|required|max:100',
                'surname'            => 'sometimes|required|max:60',
                'gender'             => 'sometimes|required',
                'status'             => 'sometimes|required|string',
                'shirt_size'         => 'sometimes|required|string',
                'birthday'           => 'sometimes|required|date|before:' . Carbon::now()->subYears(12),
                'user_id'            => 'sometimes|required|exists:users,id',
                'trip_id'            => 'sometimes|required|exists:trips,id',
                'rep_id'             => 'string|exists:reps,id',
                'avatar_upload_id'   => 'string|exists:uploads,id',
                'passport_id'        => 'string|exists:passports,id',
                'visa_id'            => 'string|exists:visas,id',
                'medical_release_id' => 'string|exists:medical_releases,id',
                'testimony_id'       => 'string|exists:essays,id',
                'tags'               => 'array',
                'costs'              => 'array',
                'costs.*.id'         => 'required|exists:costs,id',
                'costs.*.locked'     => 'boolean',
                'companion_limit'    => 'numeric',
                'weight'             => 'sometimes|required',
                'height_a'           => 'sometimes|required',
                'height_b'           => 'sometimes|required'
            ];
        }

        return $rules;
    }

    private function getShirtSizes()
    {
        return ShirtSize::sizes();
    }
}
