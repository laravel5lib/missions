<?php

namespace App\Http\Requests\v1;

use Dingo\Api\Http\FormRequest;

class RoomingPlanRequest extends FormRequest
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
            'campaign_id' => 'required|exists:campaigns,id',
            'group_ids' => 'array',
            'group_ids.*' => 'required|exists:groups,id',
            'name' => 'required|string',
            'short_desc' => 'string'
        ];

        if ($this->isMethod('put')) {
            $rules = [
                'campaign_id' => 'sometimes|required|exists:campaigns,id',
                'group_ids' => 'array',
                'group_ids.*' => 'exists:groups,id',
                'name' => 'sometimes|required|string',
                'short_desc' => 'string'
            ];
        }

        return $rules;
    }
}
