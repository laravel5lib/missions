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
        $rules = [
            'reservation_id' => 'required|exists:reservations,id',
            'items' => 'required|array',
            'items.*.transport' => 'required|array',
            'items.*.transport.type' => 'required|string|in:flight,bus,vehicle,train',
            'items.*.transport.vessel_no' => 'required|string',
            'items.*.transport.name' => 'required|string',
            'items.*.transport.call_sign' => 'string',
            'items.*.transport.domestic' => 'boolean',
            'items.*.transport.capacity' => 'numeric',
            'items.*.activity' => 'required|array',
            'items.*.activity.name' => 'required|string|max:100',
            'items.*.activity.activity_type_id' => 'required|string|exists:activity_types,id',
            'items.*.activity.description' => 'string|max:200',
            'items.*.activity.occurred_at' => 'required|date',
            'items.*.hub' => 'required|array',
            'items.*.hub.name' => 'required|string',
            'items.*.hub.call_sign' => 'required|string',
            'items.*.hub.address' => 'string',
            'items.*.hub.city' => 'required|string',
            'items.*.hub.state' => 'string',
            'items.*.hub.zip' => 'string',
            'items.*.hub.country_code' => 'required|string'
        ];

        if ($this->isMethod('put')) {
            $rules = [
                'items' => 'required|array',
                'items.*.transport' => 'sometimes|required|array',
                'items.*.transport.id' => 'required|string|exists:transports,id',
                'items.*.transport.type' => 'sometimes|required|string|in:flight,bus,vehicle,train',
                'items.*.transport.vessel_no' => 'sometimes|required|string',
                'items.*.transport.name' => 'sometimes|required|string',
                'items.*.transport.call_sign' => 'string',
                'items.*.transport.domestic' => 'boolean',
                'items.*.transport.capacity' => 'numeric',
                'items.*.activity' => 'sometimes|required|array',
                'items.*.activity.id' => 'required|string|exists:activities,id',
                'items.*.activity.activity_type_id' => 'sometimes|required|string|exists:activity_types,id',
                'items.*.activity.name' => 'sometimes|required|string|max:100',
                'items.*.activity.description' => 'string|max:200',
                'items.*.activity.occurred_at' => 'sometimes|required|date',
                'items.*.hub' => 'sometimes|required|array',
                'items.*.hub.id' => 'required|string|exists:hubs,id',
                'items.*.hub.name' => 'sometimes|required|string',
                'items.*.hub.call_sign' => 'sometimes|required|string',
                'items.*.hub.address' => 'string',
                'items.*.hub.city' => 'sometimes|required|string',
                'items.*.hub.state' => 'string',
                'items.*.hub.zip' => 'string',
                'items.*.hub.country_code' => 'sometimes|required|string'
            ];
        }

        return $rules;
    }
}
