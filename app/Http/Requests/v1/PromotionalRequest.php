<?php

namespace App\Http\Requests\v1;

use Dingo\Api\Http\FormRequest;

class PromotionalRequest extends FormRequest
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
            'reward' => 'required|numeric',
            'expires_at' => 'date|after:now',
            'promoteable_id' => 'required|string',
            'promoteable_type' => 'required|in:campaigns,groups,trips',
            'affiliates' => 'string|in:reservations',
            'code' => 'string|unique:promocodes,code,NULL,id,deleted_at,NULL'
        ];

        if ($this->isMethod('put')) {
            $rules['name'] = 'sometimes|required|string';
            $rules['reward'] = 'sometimes|required|numeric';
            $rules['promoteable_id'] = 'sometimes|required|string';
            $rules['promoteable_type'] = 'sometimes|required|in:campaigns,groups,trips';
            $rules['expires_at'] = 'date';
        }

        return $rules;
    }
}
