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
        $rules = [
            'name'             => 'required|max:100',
            'email'            => 'required|email|unique:users,email',
            'password'         => 'required|confirmed|min:8',
            'alt_email'        => 'email|unique:users,alt_email',
            'gender'           => 'in:Male,Female',
            'status'           => 'in:Single,Married',
            'birthday'         => 'date',
            'street'           => 'string|max:100',
            'city'             => 'string|max:100',
            'zip'              => 'string|max:10',
            'country_code'     => 'required|in:' . Country::codes(),
            'timezone'         => 'required|max:25',
            'url'              => 'string|unique:users,url,',
            'public'           => 'boolean',
            'bio'              => 'string|max:120',
            'banner_upload_id' => 'string|exists:uploads,id',
            'avatar_upload_id' => 'string|exists:uploads,id'
        ];

        if ($this->isMethod('put'))
        {
            $user_id = $this->route('users') ? $this->route('users') : auth()->user()->id;

            $rules['password'] = 'sometimes|required|confirmed|min:8';
            $rules['alt_email'] = 'email|unique:users,alt_email,' . $user_id;
            $rules['email'] = 'sometimes|required|email|unique:users,email,' . $user_id;
            $rules['url'] = 'string|unique:users,url,' . $user_id;
            $rules['country_code'] = 'sometimes|required|in:' . Country::codes();
            $rules['timezone'] = 'sometimes|required|max:25';
            $rules['links'] = 'array';
            $rules['links.*.name'] = 'required|string';
            $rules['links.*.url'] = 'required|string';
        }

        return $rules;
    }
}
