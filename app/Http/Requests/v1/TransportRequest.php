<?php

namespace App\Http\Requests\v1;

use Dingo\Api\Http\FormRequest;

class TransportRequest extends FormRequest
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
            'vessel_no' => 'required|string',
            'name' => 'required|string',
            'call_sign' => 'string',
            'domestic' => 'boolean',
            'capacity' => 'numeric',
            'campaign_id' => 'required|exists:campaigns,id'
        ];

        if ($this->isMethod('put')) {
            $rules['type'] = 'sometimes|required|string|in:flight,bus,vehicle,train';
            $rules['vessel_no'] = 'sometimes|required|string';
            $rules['name'] = 'sometimes|required|string';
            $rules['campaign_id'] = 'sometimes|required|exists:campaigns,id';
        }

        return $rules;
    }
}
