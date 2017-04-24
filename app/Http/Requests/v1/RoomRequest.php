<?php

namespace App\Http\Requests\v1;

use Dingo\Api\Http\FormRequest;

class RoomRequest extends FormRequest
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
            'label' => 'string',
            'room_type_id' => 'required|exists:room_types,id'
        ];

        if ($this->isMethod('put')) {
            $rules['room_type_id'] = 'sometimes|required|exists:room_types,id';
        }

        return $rules;
    }
}
