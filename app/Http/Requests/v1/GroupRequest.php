<?php

namespace App\Http\Requests\v1;

use App\Models\v1\Group;
use App\Utilities\v1\Country;
use Dingo\Api\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class GroupRequest extends FormRequest
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
            'name'         => 'required|max:100',
            'url'          => 'required_if:public,true|unique:slugs,url',
            'type'         => 'required|in:church,business,nonprofit,youth,school,independent,other',
            'timezone'     => 'required|timezone',
            'country_code' => 'required|in:' . Country::codes(),
        ];

        if ($this->isMethod('put')) {
            $required = [
                'name'         => 'sometimes|required|max:100',
                'url'          => 'sometimes|required_if:public,true|unique:slugs,url,'.$this->route('group').',slugable_id',
                'type'         => 'sometimes|required|in:church,business,nonprofit,youth,other',
                'timezone'     => 'sometimes|required|timezone',
                'country_code' => 'sometimes|required|in:' . Country::codes(),
                'status'       => 'required|in:pending,approved'
            ];
        }

        $optional = [
            'description'      => 'nullable|string',
            'address_one'      => 'nullable|string',
            'address_two'      => 'nullable|string',
            'city'             => 'nullable|string',
            'state'            => 'nullable|string',
            'zip'              => 'nullable|string',
            'phone_one'        => 'nullable|string',
            'phone_two'        => 'nullable|string',
            'email'            => 'nullable|email',
            'public'           => 'boolean',
            'managers'         => 'nullable|array',
            'tags'             => 'nullable|array',
            'avatar_upload_id' => 'nullable|string|exists:uploads,id',
            'banner_upload_id' => 'nullable|string|exists:uploads,id',
            'status'           => 'nullable|in:pending,approved'
        ];

        return $rules = $required + $optional;
    }
}
