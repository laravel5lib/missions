<?php

namespace App\Http\Requests\v1;

use Dingo\Api\Http\FormRequest;

class CampaignTransportRequest extends FormRequest
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
            'type' => 'required|string|in:flight,bus,vehicle,train',
            'vessel_no' => 'string',
            'name' => 'required|string',
            'call_sign' => 'string',
            'domestic' => 'required|boolean',
            'capacity' => 'numeric',
            'depart_at' => 'date',
            'arrive_at' => 'date',
            'departure' => 'array',
            'arrival' => 'array'
        ];

        if ($this->isMethod('put')) {
            $rules['type'] = 'sometimes|required|string|in:flight,bus,vehicle,train';
            $rules['vessel_no'] = 'sometimes|required|string';
            $rules['name'] = 'sometimes|required|string';
            $rules['domestic'] = 'sometimes|required|boolean';
            $rules['depart_at'] = 'date';
            $rules['arrive_at'] = 'date';
            $rules['departure'] = 'array';
            $rules['arrival'] = 'array';
        }

        return $rules;
    }
}
