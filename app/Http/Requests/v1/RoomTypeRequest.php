<?php

namespace App\Http\Requests\v1;

use Dingo\Api\Http\FormRequest;

class RoomTypeRequest extends FormRequest
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
            'name' => 'required|string|unique:room_types,name,NULL,id,deleted_at,NULL',
            'rules.occupancy_limit' => 'required|integer|min:1',
            'rules.married_only' => 'boolean',
            'rules.same_gender' => 'boolean',
            'campaign_id' => 'required|exists:campaigns,id'
        ];

        if ($this->isMethod('put')) {
            $rules['name'] = 'sometimes|required|string|unique:room_types,name,'.$this->route('types').',id,deleted_at,NULL';
            $rules['rules.occupancy_limit'] = 'sometimes|required|integer|min:1';
            $rules['campaign_id'] = 'sometimes|required|exists:campaigns,id';
        }

        return $rules;
    }
}
