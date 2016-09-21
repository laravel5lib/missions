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
        return [
            'given_names'        => 'required|max:100',
            'surname'            => 'required|max:60',
            'gender'             => 'required|in:male,female',
            'status'             => 'required|in:single,married',
            'shirt_size'         => 'in:' . $this->getShirtSizes(),
            'birthday'           => 'required|date|before:' . Carbon::now()->subYears(12),
            'user_id'            => 'required|exists:users,id',
            'trip_id'            => 'required|exists:trips,id',
            'rep_id'             => 'string|exists:reps,id',
            'avatar_upload_id'   => 'string|exists:uploads,id',
            'passport_id'        => 'string|exists:passports,id',
            'visa_id'            => 'string|exists:visas,id',
            'medical_release_id' => 'string|exists:medical_releases,id',
            'todos'              => 'array',
            'tags'               => 'array'
        ];
    }

    private function getShirtSizes()
    {
        return ShirtSize::sizes();
    }
}
