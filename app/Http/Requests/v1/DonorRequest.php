<?php

namespace App\Http\Requests\v1;

use App\Http\Requests\Request;
use App\Utilities\v1\Country;

class DonorRequest extends Request
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
            'company'      => 'string',
            'email'        => 'email',
            'phone'        => 'string',
            'zip'          => 'required|string',
            'country_code' => 'required|in:' . Country::codes(),
            'account_id'   => 'string',
            'account_type' => 'in:users,groups',
            'tags'         => 'array'
        ];
    }
}
