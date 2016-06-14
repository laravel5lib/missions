<?php

namespace App\Http\Requests\v1;

use App\Http\Requests\Request;

class ContactRequest extends Request
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
            'suffix'         => 'required|string|in:mr,miss,mrs,dr,ps,sir',
            'first_name'     => 'required|string',
            'last_name'      => 'string',
            'email'          => 'required_with:emergency|email',
            'phone_one'      => 'required_with:emergency|string',
            'phone_two'      => 'string',
            'address_street' => 'string',
            'address_city'   => 'string',
            'address_state'  => 'string',
            'address_zip'    => 'string',
            'country_code'   => 'string',
            'emergency'      => 'boolean',
            'relationship'   => 'required_with:emergency|string|in:guardian,family,spouse,friend,other'
        ];

        return $rules;
    }
}
