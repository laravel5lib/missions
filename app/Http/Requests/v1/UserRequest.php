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
        if ($this->isMethod('post')) {
            return [
                'first_name'       => 'required|string|max:100',
                'last_name'        => 'required|string|max:100',
                'email'            => 'required|email|unique:users,email,NULL,id,deleted_at,NULL',
                'password'         => 'required|confirmed|min:8',
                'alt_email'        => 'nullable|email',
                'gender'           => 'required|in:Male,male,Female,female',
                'status'           => 'nullable|string',
                'birthday'         => 'nullable|date',
                'street'           => 'nullable|string|max:100',
                'city'             => 'nullable|string|max:100',
                'zip'              => 'nullable|string|max:10',
                'country_code'     => 'required|in:' . Country::codes(),
                'timezone'         => 'required|max:25',
                'url'              => 'required_if:public,true|nullable|string|unique:slugs,url',
                'public'           => 'required|boolean',
                'bio'              => 'nullable|string|max:120',
                'banner_upload_id' => 'nullable|string|exists:uploads,id',
                'avatar_upload_id' => 'nullable|string|exists:uploads,id'
            ];
        }

        $user_id = $this->route('user') ? $this->route('user') : auth()->user()->id;

        return [
            'first_name'       => 'sometimes|required|string|max:100',
            'last_name'        => 'sometimes|required|string|max:100',
            'email'            => 'sometimes|required|email|unique:users,email,' . $user_id . ',id,deleted_at,NULL',
            'password'         => 'sometimes|required|confirmed|min:8',
            'alt_email'        => 'nullable|email',
            'gender'           => 'sometimes|required|in:Male,male,Female,female',
            'status'           => 'nullable|string',
            'birthday'         => 'nullable|date',
            'street'           => 'nullable|string|max:100',
            'city'             => 'nullable|string|max:100',
            'zip'              => 'nullable|string|max:10',
            'country_code'     => 'sometimes|required|in:' . Country::codes(),
            'timezone'         => 'sometimes|required|max:25',
            'url'              => 'sometimes|required_if:public,true|unique:slugs,url,'.$user_id.',slugable_id',
            'public'           => 'sometimes|required|boolean',
            'bio'              => 'nullable|string|max:120',
            'banner_upload_id' => 'nullable|string|exists:uploads,id',
            'avatar_upload_id' => 'nullable|string|exists:uploads,id'
        ];
    }
}
