<?php

namespace App\Http\Requests\v1;

use App\Utilities\v1\Country;
use Dingo\Api\Http\FormRequest;

class GroupSubmissionRequest extends FormRequest
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
            'contact'      => 'required',
            'position'     => 'required',
            'campaign'     => 'required',
            'spoke_to_rep' => 'required|string|in:yes,no',
            'name'         => 'required|max:100',
            'type'         => 'required|in:church,business,nonprofit,youth,other',
            'timezone'     => 'required|timezone',
            'country_code' => 'required|in:' . Country::codes(),
            'description'  => 'string|max:120',
            'address_one'  => 'string',
            'address_two'  => 'string',
            'city'         => 'string',
            'state'        => 'string',
            'zip'          => 'string',
            'phone_one'    => 'required|string',
            'phone_two'    => 'string',
            'email'        => 'required|email'
        ];
    }
}
