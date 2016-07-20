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
            'given_names' => 'required|max:100',
            'surname'     => 'required|max:60',
            'gender'      => 'required|in:male,female',
            'status'      => 'required|in:single,married',
            'shirt_size'  => 'required|in:' . $this->getShirtSizes(),
            'birthday'    => 'required|date|before:' . Carbon::now()->subYears(12),
            'user_id'     => 'required|exists:users,id',
            'trip_id'     => 'required|exists:trips,id'
        ];

        if ($this->isMethod('put'))
        {
            $rules = [
                'given_names' => 'sometimes|required|max:100',
                'surname'     => 'sometimes|required|max:60',
                'gender'      => 'sometimes|required|in:male,female',
                'status'      => 'sometimes|required|in:single,married',
                'shirt_size'  => 'sometimes|required|in:' . $this->getShirtSizes(),
                'birthday'    => 'sometimes|required|date|before:' . Carbon::now()->subYears(12),
                'user_id'     => 'sometimes|required|exists:users,id',
                'trip_id'     => 'sometimes|required|exists:trips,id',
            ];
        }

        $rules['rep_id'] = 'exists|reps,id';
        $rules['todos'] = 'array';
        $rules['passport'] = 'exists:passports,id';
        $rules['tags'] = 'array';

        return $rules;
    }

    private function getShirtSizes()
    {
        return ShirtSize::sizes();
    }
}
