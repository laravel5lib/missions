<?php

namespace App\Http\Requests\v1;

use App\Utilities\v1\Country;
use Dingo\Api\Http\FormRequest;

class RegionRequest extends FormRequest
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
            'name'         => 'required|string|max:100',
            'country_code' => 'sometimes|required|in:' . Country::codes(),
            'call_sign'    => 'sometimes|required|string|max:50'
        ];

        if ($this->isMethod('put'))
        {
            $rules = [
                'name'         => 'sometimes|required|string|max:100',
                'country_code' => 'sometimes|required|in:' . Country::codes(),
                'call_sign'    => 'sometimes|required|string|max:50'
            ];
        }

        return $rules;
    }
}
