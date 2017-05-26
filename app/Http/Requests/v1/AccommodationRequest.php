<?php

namespace App\Http\Requests\v1;

use App\Utilities\v1\Country;
use App\Http\Requests\Request;
use Dingo\Api\Http\FormRequest;

class AccommodationRequest extends FormRequest
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
        $required = [
            'name'         => 'required|string',
            'country_code' => 'in:' . Country::codes()
        ];

        if ($this->isMethod('put'))
        {
            $required = [
                'name'         => 'sometimes|required|string',
                'country_code' => 'in:' . Country::codes(),
                'region_id'    => 'sometimes|required|exists:regions,id',
            ];
        }

        $optional = [
            'address_one'  => 'string',
            'address_two'  => 'string',
            'city'         => 'string',
            'state'        => 'string',
            'zip'          => 'string',
            'phone'        => 'string',
            'fax'          => 'string',
            'email'        => 'email',
            'url'          => 'string',
            'short_desc'   => 'string',
            'tags'         => 'array'
        ];

        return $rules = $required + $optional;
    }
}
