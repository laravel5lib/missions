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
        if ($this->isMethod('put'))
        {
            $groupId = $this->route('groups');

            return Gate::allows('update', Group::findOrFail($groupId));
        }

        if ($this->isMethod('post'))
        {
            return $this->user()->isAdmin();
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'         => 'required|max:100',
            'description'  => 'string|max:120',
            'url'          => 'required_if:public,true',
            'type'         => 'required|in:church,business,nonprofit,youth,other',
            'timezone'     => 'required|timezone',
            'address_one'  => 'string',
            'address_two'  => 'string',
            'city'         => 'string',
            'state'        => 'string',
            'zip'          => 'string',
            'phone_one'    => 'string',
            'phone_two'    => 'string',
            'email'        => 'email',
            'country_code' => 'required|in:' . Country::codes(),
            'public'       => 'boolean',
        ];
    }
}
