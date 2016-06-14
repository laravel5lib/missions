<?php

namespace App\Http\Requests\v1;

use App\Utilities\v1\Country;
use Dingo\Api\Http\FormRequest;

class UserRequest extends FormRequest
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
        $id = null;

        if ($this->isMethod('put'))
        {
            $this->id ? $id = $this->id : $id = app('Dingo\Api\Auth\Auth')->user()->id;
        }

        $rules = [
            'name'         => 'required|max:100',
            'email'        => 'required|email|unique:users,email,' . $id,
            'password'     => 'required|confirmed|min:8',
            'alt_email'    => 'email|unique:users,alt_email,' . $id,
            'gender'       => 'in:Male,Female',
            'status'       => 'in:Single,Married',
            'birthday'     => 'date',
            'street'       => 'string|max:100',
            'city'         => 'string|max:100',
            'zip'          => 'string|max:10',
            'country_code' => 'required|in:' . Country::codes(),
            'timezone'     => 'required|max:25',
            'url'          => 'string|unique:users,url,' . $id,
            'public'       => 'boolean',
            'bio'          => 'string|max:120'
        ];

        if ($this->isMethod('post'))
        {
            $rules['password'] = 'required|confirmed|min:8';
        }

        return $rules;
    }
}
