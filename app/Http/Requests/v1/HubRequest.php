<?php

namespace App\Http\Requests\v1;

use Dingo\Api\Http\FormRequest;

class HubRequest extends FormRequest
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
            'campaign_id' => 'required|string|exists:campaigns,id',
            'name' => 'required|string',
            'call_sign' => 'string',
            'address' => 'string',
            'city' => 'string',
            'state' => 'string',
            'zip' => 'string',
            'country_code' => 'string'
        ];

        if ($this->isMethod('put')) {
            $rules['campaign_id'] = 'sometimes|required|string|exists:campaigns,id';
            $rules['name'] = 'sometimes|required|string';
        }

        return $rules;
    }
}
