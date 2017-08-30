<?php

namespace App\Http\Requests\v1;

use Dingo\Api\Http\FormRequest;

class ActivityRequest extends FormRequest
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
            'name' => 'required|string|max:100',
            'description' => 'string|max:200',
            'occurred_at' => 'required|date',
            'participant_id' => 'required|string',
            'participant_type' => 'required|string',
            'activity_type_id' => 'required|string|exists:activity_types,id',
            'transport_id' => 'string|exists:transports,id',
            'itinerary_id' => 'string|exists:itineraries,id',
            'hub_id' => 'string|exists:hubs,id'
        ];

        if ($this->isMethod('put')) {
            $rules = [
                'name' => 'sometimes|required|string|max:100',
                'description' => 'string|max:200',
                'occurred_at' => 'sometimes|required|date',
                'participant_id' => 'sometimes|required|string',
                'participant_type' => 'sometimes|required|string',
                'activity_type_id' => 'sometimes|required|string|exists:activity_types,id',
                'transport_id' => 'string|exists:transports,id',
                'itinerary_id' => 'string|exists:itineraries,id',
                'hub_id' => 'string|exists:hubs,id'
            ];
        }

        return $rules;
    }
}
