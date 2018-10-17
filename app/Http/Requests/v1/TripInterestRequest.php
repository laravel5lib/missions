<?php

namespace App\Http\Requests\v1;

use Dingo\Api\Http\FormRequest;

class TripInterestRequest extends FormRequest
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
            'trip_id' => 'required|exists:trips,id',
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'communication_preferences' => 'nullable|array',
            'communication_preferences.*' => 'nullable|in:email,phone,text',
            'status' => 'nullable|string'
        ];
    }
}
