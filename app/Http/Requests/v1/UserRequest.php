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
            'first_name'       => 'required|string|max:100',
            'last_name'        => 'required|string|max:100',
            'email'            => 'required|email|unique:users,email,NULL,id,deleted_at,NULL',
            'password'         => 'required|confirmed|min:8',
            'alt_email'        => 'nullable|email',
            'gender'           => 'in:Male,male,Female,female',
            'status'           => 'nullable|string',
            'birthday'         => 'nullable|date',
            'street'           => 'nullable|string|max:100',
            'city'             => 'nullable|string|max:100',
            'zip'              => 'nullable|string|max:10',
            'country_code'     => 'required|in:' . Country::codes(),
            'timezone'         => 'required|max:25',
            'url'              => 'nullable|string|unique:slugs,url',
            'public'           => 'boolean',
            'bio'              => 'nullable|string|max:120',
            'banner_upload_id' => 'nullable|string|exists:uploads,id',
            'avatar_upload_id' => 'nullable|string|exists:uploads,id'
        ];

        if ($this->isMethod('put')) {
            $user_id = $this->route('user') ? $this->route('user') : auth()->user()->id;

            $rules['password'] = 'sometimes|required|confirmed|min:8';
            $rules['alt_email'] = 'nullable|email';
            $rules['email'] = 'sometimes|required|email|unique:users,email,' . $user_id . ',id,deleted_at,NULL';
            $rules['url'] = 'unique:slugs,url,'.$user_id.',slugable_id';
            $rules['country_code'] = 'sometimes|required|in:' . Country::codes();
            $rules['timezone'] = 'sometimes|required|max:25';
            $rules['links'] = 'nullable|array';
            $rules['links.*.name'] = 'nullable|string';
            $rules['links.*.url'] = 'nullable|string';
        }

        return $rules;
    }
}
