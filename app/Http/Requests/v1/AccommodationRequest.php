<?php

namespace App\Http\Requests\v1;

use App\Http\Requests\Request;
use App\Utilities\v1\Country;

class AccommodationRequest extends Request
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
            'name'         => 'required|string',
            'address_one'  => 'string',
            'address_two'  => 'string',
            'city'         => 'string',
            'state'        => 'string',
            'zip'          => 'string',
            'phone'        => 'string',
            'fax'          => 'string',
            'country_code' => 'required|in:' . Country::codes(),
            'email'        => 'email',
            'url'          => 'string',
            'region_id'    => 'required|exists:regions,id',
            'short_desc'   => 'string',
            'check_in_at'  => 'date',
            'check_out_at' => 'date'
        ];
    }
}
