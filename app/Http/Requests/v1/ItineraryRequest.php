<?php

namespace App\Http\Requests\v1;

use Dingo\Api\Http\FormRequest;

class ItineraryRequest extends FormRequest
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
            'name' => 'required|string',
            'itinerant_id' => 'required|string',
            'itinerant_type' => 'required|in:reservations,trips,campaigns,groups'
        ];

        if ($this->isMethod('Put')) {
            $rules = [
                'name' => 'sometimes|required|string',
                'itinerant_id' => 'sometimes|required|string',
                'itinerant_type' => 'sometimes|required|in:reservations,trips,campaigns,groups'
            ];
        }

        return $rules;
    }
}
