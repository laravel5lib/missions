<?php

namespace App\Http\Requests\v1;

use App\Models\v1\Group;
use App\Utilities\v1\Country;
use Dingo\Api\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class GroupRequest extends FormRequest {

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
            'name'         => 'required|max:100',
            'url'          => 'required_if:public,true|unique:slugs,url',
            'type'         => 'required|in:church,business,nonprofit,youth,other',
            'timezone'     => 'required|timezone',
            'country_code' => 'required|in:' . Country::codes(),
        ];

        if ($this->isMethod('put'))
        {
            $required = [
                'name'         => 'sometimes|required|max:100',
                'url'          => 'sometimes|required_if:public,true|unique:slugs,url,'.$this->route('groups').',slugable_id',
                'type'         => 'sometimes|required|in:church,business,nonprofit,youth,other',
                'timezone'     => 'sometimes|required|timezone',
                'country_code' => 'sometimes|required|in:' . Country::codes(),
                'status'       => 'required|in:pending,approved'
            ];
        }

        $optional = [
            'description'      => 'string|max:120',
            'address_one'      => 'string',
            'address_two'      => 'string',
            'city'             => 'string',
            'state'            => 'string',
            'zip'              => 'string',
            'phone_one'        => 'string',
            'phone_two'        => 'string',
            'email'            => 'email',
            'public'           => 'boolean',
            'managers'         => 'array',
            'tags'             => 'array',
            'avatar_upload_id' => 'string|exists:uploads,id',
            'banner_upload_id' => 'string|exists:uploads,id',
            'status'           => 'in:pending,approved'
        ];

        return $rules = $required + $optional;
    }
}
