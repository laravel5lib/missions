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
            'name' => 'required|string|unique:room_types,name',
            'rules.occupancy' => 'required|integer|min:1'
        ];

        if ($this->isMethod('put')) {
            $rules['name'] = 'sometimes|required|string|unique:room_types,name,'.$this->route('types');
            $rules['rules.occupancy'] = 'sometimes|required|integer|min:1';
        }

        return $rules;
    }
}
