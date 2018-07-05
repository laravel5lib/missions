<?php

namespace App\Http\Requests\v1;

use App\Utilities\v1\Country;
use Dingo\Api\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegionRequest extends FormRequest
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
            'name'        => [
                'required', 
                'string', 
                'max:100',
                Rule::unique('regions')->where(function ($query) {
                    return $query->where('campaign_id', $this->campaign_id);
                })
            ],
            'campaign_id' => 'required|exists:campaigns,id'
        ];

        if ($this->isMethod('put')) {
            $rules = [
                'name'        => [
                    'sometimes', 
                    'required', 
                    'string', 
                    'max:100',
                    Rule::unique('regions')->where(function ($query) {
                        return $query->where('campaign_id', $this->campaign_id);
                    })->ignore($this->route('region'))
                ],
                'campaign_id' => 'sometimes|required|exists:campaigns,id'
            ];
        }

        return $rules;
    }
}
