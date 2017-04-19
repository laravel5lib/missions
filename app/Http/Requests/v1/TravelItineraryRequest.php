<?php

namespace App\Http\Requests\v1;

use Dingo\Api\Http\FormRequest;

class TravelItineraryRequest extends FormRequest
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
            'reservation_id' => 'required|exists:reservations,id',
            'transport' => 'required|array',
            'transport.type' => 'required|string|in:flight,bus,vehicle,train',
            'transport.vessel_no' => 'required|string',
            'transport.name' => 'required|string',
            'transport.call_sign' => 'string',
            'transport.domestic' => 'boolean',
            'transport.capacity' => 'numeric',
            'activity' => 'required|array',
            'activity.name' => 'required|string|max:100',
            'activity.description' => 'string|max:200',
            'activity.occurred_at' => 'required|date'
        ];
    }
}
