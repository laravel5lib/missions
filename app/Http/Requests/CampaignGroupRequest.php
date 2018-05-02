<?php

namespace App\Http\Requests;

use Dingo\Api\Http\FormRequest;

class CampaignGroupRequest extends FormRequest
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
            'group_id' => 'required|exists:groups,id',
            'status_id' => 'required|numeric'
        ];
    }

    public function messages()
    {
        return [
            'group_id.required' => 'Please select an organization.',
            'status_id.required' => 'Please choose a status'
        ];
    }
}
