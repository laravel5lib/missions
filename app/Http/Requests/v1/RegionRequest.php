<?php

namespace App\Http\Requests\v1;

use App\Utilities\v1\Country;
use Dingo\Api\Http\FormRequest;

class RegionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $required = [
            'name'         => 'required|string|max:100',
            'country_code' => 'required|in:' . Country::codes(),
            'call_sign'    => 'required|string|max:50',
            'campaign_id'  => 'required|exists:campaigns,id'
        ];

        if ($this->isMethod('put'))
        {
            $required = [
                'name'         => 'sometimes|required|string|max:100',
                'country_code' => 'sometimes|required|in:' . Country::codes(),
                'call_sign'    => 'sometimes|required|string|max:50',
                'campaign_id'  => 'sometimes|required|exists:campaigns,id'
            ];
        }

        $optional = [
            'tags' => 'array'
        ];

        return $rules = $required + $optional;
    }
}
