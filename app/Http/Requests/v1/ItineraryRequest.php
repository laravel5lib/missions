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
            'curator_id' => 'required|string',
            'curator_type' => 'required|in:reservations,trips,campaigns,groups,transports,accommodations'
        ];

        if ($this->isMethod('Put')) {
            $rules = [
                'name' => 'sometimes|required|string',
                'curator_id' => 'sometimes|required|string',
                'curator_type' => 'sometimes|required|in:reservations,trips,campaigns,groups,transports,accommodations'
            ];
        }

        return $rules;
    }
}
